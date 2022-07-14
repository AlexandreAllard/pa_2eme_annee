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
//check if stripe token exist to proceed with payment
ini_set('display_errors',1);

//include('includes/head.php');
  if(!empty($_POST['stripeToken'])){
    // get token and user details
    $stripeToken  = $_POST['stripeToken'];
    $user_lastname = $_POST['nom'];
    $user_firstname = $_POST['prenom'];
    $mail =$_POST['mail'];
    $address = $_POST['adresse'];
    $fidelity_use = $_POST['fidelity_use'];
    $user_fidelity = $_POST['user_fidelity'];
    $forfait_prix_euro = $_POST['forfait_prix_euro'];



    //include Stripe PHP library
    require_once('vendor/stripe/stripe-php/init.php');
    //set stripe secret key and publishable key
    $stripe = array(
      "secret_key"      => "sk_test_51KwQzsKlgSGtYbAEAlMzv1hwOQcFz557fzskk4imD0xab6ah7SfYILunvqUi89JM5SR8taricHxT5TmyLM5Zx8o200oDkS0gLB",
      "publishable_key" => "pk_test_51KwQzsKlgSGtYbAEtA4Rpg27zk7sqlpziHtkK2XRz4uuXE3pGwFmv3c5sKsqyHqI9BSPiYCdqhJOjJ7CRC6UgSwF00rCxO6YmU"
    );
    \Stripe\Stripe::setApiKey($stripe['secret_key']);

    //add customer to stripe
    $customer = \Stripe\Customer::create(array(
        'email' => $mail,
        'source'  => $stripeToken
    ));


//Données du produit acheté
$id_forfait=$_SESSION['id_forfait_select']; //id du produit que le souhaite payer
$id_client=$_SESSION['id'];

// Info forfait
$connection = connectDB();
$forfaitParID = $connection->prepare("SELECT * FROM caf_forfait WHERE id_forfait=:id_forfait");
$forfaitParID->execute(["id_forfait"=>$id_forfait]);
$forfait = $forfaitParID ->fetch();
$forfaitNom = $forfait['nom_forfait'];
$paiement_immediat = $forfait['paiement_immediat'];
$duree_reservation = $forfait['temps_course'];
$temps_expiration_heures = $forfait['temps_expiration_heures'];
$currency = "EUR";
date_default_timezone_set('Europe/Paris');
$date_reservation = date("Y-m-d H:i:s");
$date_expiration = date('Y-m-d H:i:s',strtotime('+'.$temps_expiration_heures.'hours'));


/*
$connection = connectDB();
$produitById = $connection->prepare("SELECT * FROM caf_produit WHERE id_produit=:id_produit");
$produitById->execute(["id_produit"=>$idItemSelect]);
$produit = $produitById ->fetch();
$itemName = $produit['nom_produit'];
$itemPrice = $produit['prix_produit']*100;;
$currency = "EUR";
$itemID = $produit['id_produit'];
*/

if ($fidelity_use <= 0) {
  $fidelity_use=0;
}

// Prix final en €
$final_price_euro=finalPrice($forfait_prix_euro, $fidelity_use);
// Prix version Stripe
$final_price_stripe= $final_price_euro*100;

// Point fidélité
// Calcul point fidélité
$pt_fidelite=calculFidelite($final_price_euro);

    // détails du paiement
    $payDetails = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount'   => $final_price_stripe,
        'currency' => $currency,
        'description' => $forfaitNom,
        'metadata' => array(
            'order_id' => $id_forfait
        )
    ));




    // obtenir les détails du paiement
    $paymentResponse = $payDetails->jsonSerialize();
    // vérifier si le paiement est réussi
    if($paymentResponse['amount_refunded'] == 0 && empty($paymentResponse['failure_code']) && $paymentResponse['paid'] == 1 && $paymentResponse['captured'] == 1){
        // details transaction
        $amountPaid = $paymentResponse['amount'];
        $balanceTransaction = $paymentResponse['balance_transaction'];
        $paidCurrency = $paymentResponse['currency'];
        $paymentStatus = $paymentResponse['status'];
        date_default_timezone_set('Europe/Paris');
        $paymentDate = date("Y-m-d H:i:s");

        // Numero de commande UNIQUE
        do {
          $num_commande = rand(1000000, 9999999);
          $req = $connection->prepare("SELECT * FROM caf_facture WHERE numero_de_commande=?");
          $req->execute([$num_commande]);
          $num = $req->fetch();
        } while ($num);

        $deliveryDate=date('Y-m-d',strtotime('+7 days'));

        ?>
        <div class="text-center">
        <?php
        //TODO
        echo "<h3>Adjugé vendu : ".$forfaitNom." au prix de ".$final_price_euro."€ à ".$user_firstname." ".$user_lastname."</h4>";
        echo "<br>Votre forfait expirera automatiquement le ".$date_expiration;
        echo "<br>Adresse mail : ".$mail;
        echo "<br> N° commande : ".$num_commande;


        echo "<br>Statut du paiement : ".$paymentStatus;
        echo "<br>Acheté le ".$paymentDate;




        echo "<br><h4>"."Tu as gagné ".$pt_fidelite." points de fidélité"."</h4>";
        echo "<br> Nouveau solde de point de fidélité : ".($user_fidelity+$pt_fidelite-$fidelity_use);

        ?>
        <br><br>
          <a class="btn btn-outline-dark mt-auto" href="../reservations.php">Voir mes réservations</a>
          <a class="btn btn-outline-dark mt-auto" href="../pricing.php">Retour à la boutique</a>
        </div>
        <?php







        $id_client = $_SESSION['id'];
        // Points fidelité gagnés
        $req_point_fidelite="UPDATE caf_utilisateur set points_fidelite = points_fidelite + $pt_fidelite where id_client = $id_client";
        $req=$connection->prepare($req_point_fidelite);
        $req->execute();

        // Points fidelité perdus
        $req_point_fidelite="UPDATE caf_utilisateur set points_fidelite = points_fidelite - $fidelity_use where id_client = $id_client";
        $req=$connection->prepare($req_point_fidelite);
        $req->execute();

        // Insertion Facture dans BDD
        $insertTransactionSQL = "INSERT INTO caf_facture(montant_facture, id_client, prenom_client, nom_client, adresse_mail_client, id_produit, nom_produit, numero_de_commande)
    		VALUES('$final_price_euro','$id_client','$user_firstname','$user_lastname','$mail','$id_forfait','$forfaitNom','$num_commande')";
        $req=$connection->prepare($insertTransactionSQL);
        $req->execute();

        // Insertion Commande dans BDD
        $type_commande="forfait";
        $insert_commande = "INSERT INTO caf_commande(montant_commande, type_commande, point_fidelite_utilise, id_client, id_produit, nom_produit, numero_de_commande)
    		VALUES('$final_price_euro', '$type_commande', '$fidelity_use', '$id_client','$id_forfait','$forfaitNom','$num_commande')";
        $req=$connection->prepare($insert_commande);
        $req->execute();

        // Insertion Réservation dans BDD
        $req_search_plan= $connection->prepare("SELECT * FROM caf_forfait WHERE id_forfait=:id_forfait");
        $req_search_plan->execute(["id_forfait"=>$id_forfait]);
        $planInfos = $req_search_plan->fetch();
        $planNbCourses = $planInfos['nb_courses'];

        if($planNbCourses != NULL){
          $insert_commande = "INSERT INTO caf_reservation(id_client, nb_courses, etat_reservation, prix_reservation, duree_reservation, date_reservation, date_expiration, id_forfait)
          VALUES('$id_client', '$planNbCourses', '1', '$final_price_euro', '$duree_reservation', '$date_reservation', '$date_expiration','$id_forfait')";
          $req=$connection->prepare($insert_commande);
          $req->execute();
        }else{
          $insert_commande = "INSERT INTO caf_reservation(id_client, etat_reservation, prix_reservation, duree_reservation, date_reservation, date_expiration, id_forfait)
          VALUES('$id_client', '1', '$final_price_euro', '$duree_reservation', '$date_reservation', '$date_expiration','$id_forfait')";
          $req=$connection->prepare($insert_commande);
          $req->execute();
        }









      }//TODO

}//TODO
 ?>




</body>
</html>
