<?php
session_start();
//Factorisation
include "header.php";
require "functions.php";
?>

  <main class="flex-shrink-0">
      <!-- Navigation-->
      <?php if(isset($_SESSION['statut']) ){
        if($_SESSION['statut'] == "collecteur"){
      		navbarCollector();
        }else{
          navbar();
        }
    	}else{
    		navbar();
    	}  ?>
      <!-- Header-->
      <header class="py-5">
          <div class="container px-5">
              <div class="row justify-content-center">
                  <div class="col-lg-8 col-xxl-6">
                      <div class="text-center my-5">
                          <h1 class="fw-bolder mb-3">Mon compte</h1>
                          <p class="lead fw-normal text-muted mb-4">Modifier...</p>
                          <a class="btn btn-primary btn-lg" href="#scroll_modify_infos">Informations personnelles</a>
                          <a class="btn btn-primary btn-lg" href="#scroll_modify_password">Mot de passe</a>
                      </div>
                  </div>
              </div>
          </div>
      </header>

        <div classe="rightPage">
          <form class="user" action="modify_infos.php" method="POST" id="scroll_modify_infos">
            <div class="comble">
              <h1 class="title">Modifier mes informations</h1>
            </div>
            <?php if(isset($_SESSION["listOfErrors"])){ ?>
                <div style="background-color:#ad5555; color: white; padding: 10px; margin: 10px; ">
            <?php
                foreach ($_SESSION["listOfErrors"] as $error) {
                echo "<li>".$error;
                }
                unset($_SESSION["listOfErrors"]);
            ?>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION["listSuccess"])){ ?>

                <div style="background-color:#46B921; color: white; padding: 10px; margin: 10px; ">
            <?php
                foreach ($_SESSION["listSuccess"] as $success) {
                echo "<li>".$success;
                }
                unset($_SESSION["listSuccess"]);
            ?>
                </div>
            <?php }
                ?>

            <?php
                 $connection = connectDB();
                 $id_client = $_SESSION["id"];
                 // la requête
                  $req = $connection->prepare("SELECT * FROM caf_utilisateur WHERE id_client=:id_client");
                  $req->execute(["id_client"=>$id_client]);

                  $infos_client = $req->fetch();
              ?>

            <div class="container1">
              <div class="text">
                  <input type="text" class="inputField" id="lastName"
                      name="lastName" placeholder="Nom"
                      value="<?php echo $infos_client['nom'];?>" required>
              </div>
                <div class="text">
                    <input type="text" class="inputField" id="firstName"
                        name="firstName" placeholder="Prénom"
                        value="<?php echo $infos_client['prenom'];?>" required>
                </div>
            </div>
            <div class="container2 text">
                <input type="email" class="inputField" id="email"
                    name="email" placeholder="Adresse mail"
                    value="<?php echo $infos_client['adresse_mail'];?>" required>
            </div>
            <div class="container1">
                <div class="text">
                    <input type="date" class="inputField" id="birthdate"
                    name="birthdate" placeholder="Date de naissance"
                    value="<?php echo $infos_client['date_de_naissance'];?>" required>
                </div>
                <div class="text">
                    <input type="tel" class="inputField" id="phone"
                    name="phone" placeholder="Téléphone" pattern="^0[1-9]([ -]?[0-9]{2}){4}$"
                    value="<?php echo $infos_client['numero_de_telephone'];?>" required>
                </div>
            </div>
            <div class="container3 text">
                <input type="text" class="inputField" id="numWay"
                    name="numWay" placeholder="Numéro"
                    value="<?php echo $infos_client['numero_voie'];?>" required>
                <input type="text" class="inputField" id="typeWay"
                    name="typeWay" placeholder="Type de voie"
                    value="<?php echo $infos_client['type_voie'];?>"  required>
                <input type="text" class="inputField" id="nameWay"
                    name="nameWay" placeholder="Nom de voie"
                    value="<?php echo $infos_client['nom_voie'];?>" required>
            </div>
            <div class="container1">
                <div class="text">
                    <input type="postalCode" class="inputField" id="postalCode"
                    name="postalCode" placeholder="Code postal"
                    value="<?php echo $infos_client['code_postal'];?>" required>
                </div>
                <div class="text">
                    <input type="text" class="inputField" id="city"
                    name="city" placeholder="Ville"
                    value="<?php echo $infos_client['ville'];?>" required>
                </div>
            </div>
            <div class="container2 text">
                <input type="text" class="inputField" id="moreInfos"
                    name="moreInfos" placeholder="Informations supplémentaires"
                    value="<?php echo $infos_client['indication_supplementaire'];?>">
            </div>
            <div class="container4">
                <input type="submit" value="Valider mes informations" name="submit" class="button"></input>
            </div>
            <br>
          </form>


                <div class="comble">
                  <h1 class="title">Modifier mon mot de passe</h1>
                </div>
                <?php

                    if(isset($_SESSION["listOfErrorsPassword"])){ ?>

                        <div style="background-color:#ad5555; color: white; padding: 10px; margin: 10px; ">
                    <?php
                        foreach ($_SESSION["listOfErrorsPassword"] as $error) {
                        echo "<li>".$error;
                        }
                        unset($_SESSION["listOfErrorsPassword"]);
                    ?>
                        </div>
                    <?php }
                        ?>

                    <?php if(isset($_SESSION["listSuccessPassword"])){ ?>

                        <div style="background-color:#46B921; color: white; padding: 10px; margin: 10px; ">
                    <?php
                        foreach ($_SESSION["listSuccessPassword"] as $success) {
                        echo "<li>".$success;
                        }
                        unset($_SESSION["listSuccessPassword"]);
                    ?>
                        </div>
                    <?php }
                        ?>

                <form class="user" action="modify_password.php" method="POST" id="scroll_modify_password">
                  <div class="container2 text">
                      <input type="password" class="inputField" id="pwd_old"
                          name="pwd_old" placeholder="Ancien mot de passe" required>
                  </div>
                  <div class="container1">
                      <div class="text">
                          <input type="password" class="inputField" id="new_pwd"
                          name="new_pwd" placeholder="Nouveau mot de passe" required>
                      </div>
                      <div class="text">
                          <input type="password" class="inputField" id="new_pwd_conf"
                          name="new_pwd_conf" placeholder="Confirmation" required>
                      </div>
                  </div>
                  <div class="container4">
                      <input type="submit" value="Changer mon mot de passe" class="button" name="submit"></input>
                  </div>
                </form>
            <hr>
          </div>
      </div>
    </div>

  </main>
  <!-- Footer-->
  <footer class="bg-dark py-4 mt-auto">
      <div class="container px-5">
          <div class="row align-items-center justify-content-between flex-column flex-sm-row">
              <div class="col-auto"><div class="small m-0 text-white">Copyright &copy; Trotterfly 2022</div></div>
              <div class="col-auto">
                  <a class="link-light small" href="#!">Politique de confidentialité</a>
                  <span class="text-white mx-1">&middot;</span>
                  <a class="link-light small" href="#!">A propos</a>
                  <span class="text-white mx-1">&middot;</span>
                  <a class="link-light small" href="#!">Contact</a>
              </div>
          </div>
      </div>
  </footer>
  <!-- Bootstrap core JS-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Core theme JS-->
  <script src="js/scripts.js"></script>


<?php
  include "footer.php";
?>
