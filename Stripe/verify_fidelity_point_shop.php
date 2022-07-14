<?php
session_start();
require "../functions.php";
?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>TROTTERFLY</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="../assets/Logo_orange.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../css/styles.css" rel="stylesheet" />
    </head>
    <body class="d-flex flex-column h-100">


<?php
    echo '<body style="background-color: #fd7e14;">';
    echo '<div style="color: #fff;">';
    echo '<div style="font-size: 16px ;">';
    
  $user_fidelity = $_POST["user_fidelity"];
  $fidelity_use = $_POST["fidelity_use"];
  $nb_fidelity_max = $_POST['nb_fidelity_max'];
  $userId = $_SESSION['id'];


	$listOfErrorsAchat = [];
	$listSuccess = [];

  $connection = connectDB();

if ($fidelity_use > $user_fidelity) {
  $listOfErrorsAchat[] =  "Ton nombre de point saisit est supérieur au nombre de point que tu possèdes !";
}

if ($fidelity_use > $nb_fidelity_max) {
  $listOfErrorsAchat[] =  "Ton nombre de point saisit dépasse le nombre de point possible pour cet achat !";
}

	//Si $listOfErrorsAchat est vide le formulaire est OK
	if( empty($listOfErrorsAchat) ){

    $stripeToken  = $_POST['stripeToken'];
    $fidelity_use = $_POST['fidelity_use'];
    $user_fidelity = $_POST['user_fidelity'];
    $address = $_POST['adresse'];
    $user_lastname = $_POST['nom'];
    $user_firstname = $_POST['prenom'];
    $mail =$_POST['mail'];
    $id_produit = $_POST['id_produit'];


    // Info produit
    $connection = connectDB();
    $produitById = $connection->prepare("SELECT * FROM caf_produit WHERE id_produit=:id_produit");
    $produitById->execute(["id_produit"=>$id_produit]);
    $produit = $produitById ->fetch();
    $itemName = $produit['nom_produit'];
    $itemPrice = $produit['prix_produit']*100;
    $currency = "EUR";
    $itemID = $produit['id_produit'];
    $deliveryDate=date('Y-m-d',strtotime('+7 days'));

    // Affichage reduction des points
    if ($fidelity_use <= 0) {
      $fidelity_use=0;
    }
    $final_price=finalPrice($produit['prix_produit'],$fidelity_use );
    $pt_fidelite_suppl=calculFidelite($final_price);



?>
<div class="text-center">
<?php
    echo "<h1>Récapitulatif</h1>";
    echo "<br>Produit : ".$itemName;
    echo "<br>Prix initial : ".$produit['prix_produit']." € ";
    echo "<br>Prix final : ".$final_price." €";
    echo "<br>Réduction grace aux points de fidélité : -".($fidelity_use*0.2)." €";
    echo "<br>---";
    echo "<br>Nom : ".$user_lastname;
    echo "<br>Prénom : ".$user_firstname;
    echo "<br>Mail : ".$mail;
    echo "<br>Adresse de livraison : ".$address;
    echo "<br>---";
    echo "<br>Points de fidélité actuel : ".$user_fidelity;
    echo "<br>Points de fidélité utilisés : ".$fidelity_use;
    echo "<br>Points de fidélité gagnés après achat : ".$pt_fidelite_suppl;
    echo "<br>Points de fidélité après achat : ".($user_fidelity-$fidelity_use+$pt_fidelite_suppl);


?>
</div>



    <form action="payment_shop.php" method="post" id="payment-form">

      <div id="card-element">

      </div>
      <div id="card-errors" role="alert"></div>
      <input type="hidden" value="<?php echo $user_fidelity;?>" name="user_fidelity">
      <input type="hidden" value="<?php echo $fidelity_use;?>" name="fidelity_use">
      <input type="hidden" value="<?php echo $address;?>" name="adresse">
      <input type="hidden" value="<?php echo $stripeToken;?>" name="stripeToken">
      <input type="hidden" value="<?php echo $user_lastname;?>" name="nom">
      <input type="hidden" value="<?php echo $user_firstname;?>" name="prenom">
      <input type="hidden" value="<?php echo $mail;?>" name="mail">
      <input type="hidden" value="<?php echo $id_produit;?>" name="id_produit">
      <br>

      <div class="text-center">
        <button class="btn btn-outline-dark mt-auto" type="submit" action="payment_shop.php">Je valide mon achat</button>
        <a class="btn btn-outline-dark mt-auto" href="../shop.php">J'annule mon achat</a>
      </div>

    </form>
    <?php
}

	//Sinon il y a eu des erreurs
	if(!empty($listOfErrorsAchat)) {
		//Afficher les erreurs sur la page achat_shop.php
		$_SESSION["listOfErrorsAchat"] = $listOfErrorsAchat;
		header("Location: achat_shop.php");
	}
  ?>




</body>
</html>
