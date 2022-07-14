<?php
session_start();
require "../functions.php";
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
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
  <body>

    <?php
echo '<body style="background-color: #fd7e14;">';
echo '<div style="color: #000;">';
echo '<div style="font-size: 16px ;">';

    //print_r($_POST);
    if(!empty($_POST)){
      $_SESSION["id_forfait_select"] = $_POST["id_forfait_select"];//stocké en session pour pouvoir la réutiliser facilement en cas d'erreur avec les infos données
    }

    // ID du forfait que le souhaite payer
    $id_forfait_select=$_SESSION["id_forfait_select"];

    // Info forfait
    $connection = connectDB();
    $forfaitParID = $connection->prepare("SELECT * FROM caf_forfait WHERE id_forfait=:id_forfait");
    $forfaitParID->execute(["id_forfait"=>$id_forfait_select]);
    $forfait = $forfaitParID ->fetch();
    $forfaitNom = $forfait['nom_forfait'];
    $paiement_immediat = $forfait['paiement_immediat'];
    $forfaitPrixJour = $forfait['prix_jour'];
    $forfaitPrixMois = $forfait['prix_mois'];
    $forfaitPrixAn = $forfait['prix_an'];
    $currency = "EUR";


    if ($forfaitPrixJour>0){
      $forfait_prix_euro=$forfaitPrixJour;
    }else if ($forfaitPrixMois>0){
      $forfait_prix_euro=$forfaitPrixMois;
    }else if ($forfaitPrixAn>0) {
      $forfait_prix_euro=$forfaitPrixAn;
    }

    // Info user
    $info_user = $connection->prepare("SELECT * FROM caf_utilisateur WHERE id_client=:id_client");
    $info_user->execute(["id_client"=>$_SESSION['id']]);
    $user = $info_user ->fetch();
    $user_firstname=$user['prenom'];
    $user_lastname=$user['nom'];
    $user_mail=$user['adresse_mail'];
    $user_fidelity=$user['points_fidelite'];






    if ($paiement_immediat==1) {



          // Calcul point fidélité
          $pt_fidelite=calculFidelite($forfait_prix_euro);
          $max_reduc_euro=$forfait_prix_euro*0.9; // Reducion avec point fidélité = -90%
          $nb_fidelity_max=$max_reduc_euro/0.2;  // Nombre de point de fidélité maximum pour l'achat
          $nb_fidelity_max= (int)$nb_fidelity_max;
          ?>
    
          <div class="text-center">
                <div style="position: relative; width: 50%; height: 1050px; background-color: #FFF; left: 25%; display: grid; place-items: center; align-content: space-evenly;">
      <?php
          echo $user_firstname.' '.$user_lastname;
          echo "<br> Tu gagneras ";
          echo $pt_fidelite;
          echo " points de fidélité";

          echo "<br>Ton solde de point de fidélité actuel : ".$user_fidelity;
          ?>

          <h3><?php echo "Forfait : ".$forfaitNom." | Prix : ".$forfait_prix_euro."€"."<br>" ; ?></h3>

          <?php if(isset($_SESSION["listOfErrorsAchat"])){ ?>
              <div style="background-color:#ad5555; color: white; padding: 10px; margin: 10px; ">
          <?php
              foreach ($_SESSION["listOfErrorsAchat"] as $error) {
              echo "<li>".$error;
              }
              unset($_SESSION["listOfErrorsAchat"]);
          ?>
              </div>
          <?php } ?>

          <form action="verify_fidelity_point_forfait.php" method="post" id="payment-form">
            <div>
                <?php echo "Nombre de point de fidélité maximum pour cet achat : ".$nb_fidelity_max." (correspond à -90%)"; ?>
                <p>Utiliser vos points de fidélité :</p>
                <input type="number" placeholder="1 point = 0,2€" name="fidelity_use"></input>
            </div>
            <div>
              <lapel for="adresse"><input type="text" name="adresse" placeholder="Adresse de livraison " required></label>
            </div>
            <div class="form-row">
              <label for="credit_element">Credit or debit card</label>
            </div>
            <div id="card-element" style="background-color: #FFF; width: 250px; position: relative; left: 150px;">

            </div>
            <div id="card-errors" role="alert"></div>
            <input type="hidden" value="<?php echo $user_fidelity;?>" name="user_fidelity">
            <input type="hidden" value="<?php echo $user_lastname;?>" name="nom">
            <input type="hidden" value="<?php echo $user_firstname;?>" name="prenom">
            <input type="hidden" value="<?php echo $user_mail;?>" name="mail">
            <input type="hidden" value="<?php echo $nb_fidelity_max;?>" name="nb_fidelity_max">
            <input type="hidden" value="<?php echo $forfait_prix_euro;?>" name="forfait_prix_euro">


            <br>
            <button>Valider le paiement</button>
          </form>


          <div class="text-center">
            <a class="btn btn-outline-dark mt-auto" href="../pricing.php">Retour à la boutique</a>
          </div>
          </div>
    </div>























    <?php
    }else { // Forfait à la minute
    ?>
      <div class="text-center">
        <h1>En cours de développement</h1>
        <a class="btn btn-outline-dark mt-auto" href="../pricing.php">Retour à la boutique</a>
      </div>
    <?php
    }
    ?>












    <script src="https://js.stripe.com/v3/"> </script>
    <script type="text/javascript" src="java.js"></script>

    </script>
    <script>
      var stripe = Stripe('pk_test_51KwQzsKlgSGtYbAEtA4Rpg27zk7sqlpziHtkK2XRz4uuXE3pGwFmv3c5sKsqyHqI9BSPiYCdqhJOjJ7CRC6UgSwF00rCxO6YmU');
      var elements = stripe.elements();
      var style = {
        base: {
          frontSize: '16px',
          color: "#32325d",
        }
      };
      var card =elements.create('card', {style: style});
      card.mount('#card-element');

      card.addEventListener('change', function (event) {
        var displayError = document.getElementById('card-errors');
        if(event.error) {
          displayError.textContent = event.error.message;
        }else{
          displayError.textContent = '';
        }
      });

      var form = document.getElementById('payment-form');
      form.addEventListener('submit', function (event){
        event.preventDefault();

        stripe.createToken(card).then(function (result) {
          if (result.error) {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
          }else{
            stripeTokenHandler(result.token);
          }
        });
      });

      function stripeTokenHandler(token) {
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type','hidden');
        hiddenInput.setAttribute('name','stripeToken');
        hiddenInput.setAttribute('value',token.id);
        form.appendChild(hiddenInput);

        form.submit();
      }
    </script>
  </body>
</html>
