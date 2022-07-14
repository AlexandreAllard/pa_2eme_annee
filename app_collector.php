<?php
session_start();
/*
etat_trottinette
0 = fonctionnel
1 = besoin de révision (+150km)
2 = besoin de reparation (cassée)
3 = en reparation
4 = hors service (poubelle)
*/

//Factorisation
include "header.php";
require "functions.php";

if(!isset($_SESSION['statut']) || $_SESSION['statut'] != "collecteur" || !isset($_SESSION['prenom'])
) {

    header("Location: 403.php");


} else {

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
    	} ?>
      <!-- Header-->
      <header class="py-5">
          <div class="container px-5">
              <div class="row justify-content-center">
                  <div class="col-lg-8 col-xxl-6">
                      <div class="text-center my-5">
                          <h1 class="fw-bolder mb-3">Application collecteur</h1>
                      </div>
                  </div>
              </div>
          </div>
      </header>

      <!-- Features section-->
      <section class="py-5" id="features">
          <div class="container">
              <div class="row gx-5">

                  <?php
                  if (isset($_POST["submit_lister"])) {
                    $connection = connectDB();
                    $trottinettes = $connection->query('SELECT * FROM caf_trottinette WHERE etat_trottinette = 1 ');
                    echo "Besoin de révision (+150km) : ";
                    while($trottinette = $trottinettes->fetch()) { ?>
                        <li>

                            <?php echo "id : ".$trottinette['id_trottinette'] ?><br>
                            <?php echo "etat_trottinette : " . $trottinette['etat_trottinette'] ?><br>
                            <?php echo "coordonnees : " . $trottinette['latitude'] . ", " . $trottinette['longitude'] ?><br>
                            <?php echo "Distance parcourue totale : " . $trottinette['km']."km" ?>
                            <hr>
                        </li>
                    <?php }
                  }

                  ?><br><?php

                  if (isset($_POST["submit_lister"])) {
                    $connection = connectDB();
                    $trottinettes = $connection->query('SELECT * FROM caf_trottinette WHERE etat_trottinette = 2 ');
                    echo "Besoin de réparation : ";
                    while($trottinette = $trottinettes->fetch()) { ?>
                        <li>

                            <?php echo $trottinette['id_trottinette'] ?> :
                            <?php echo $trottinette['etat_trottinette'] ?>
                            <?php echo $trottinette['latitude'] . ", " . $trottinette['longitude'] ?>
                            <?php echo $trottinette['km']."km" ?>
                            <hr>
                        </li>
                    <?php }
                  }



                  if (isset($_POST["submit_recup_trot"])) {
                  $id=$_POST["id_trottinette"];
                    $connection = connectDB();
                    $updateStatus = $connection->prepare("UPDATE `caf_trottinette` SET `etat_trottinette`= 3 WHERE id_trottinette=:id_trottinette ");
                    $updateStatus->execute(["id_trottinette"=>$id]);
                    echo "Trottinette ".$id." passée en réparation";
                  }

                  if (isset($_POST["submit_mise_service_trot"])) {
                  $id=$_POST["id_trottinette"];
                    $connection = connectDB();
                    $updateStatus = $connection->prepare("UPDATE `caf_trottinette` SET `etat_trottinette`= 0 WHERE id_trottinette=:id_trottinette ");
                    $updateStatus->execute(["id_trottinette"=>$id]);
                    echo "Trottinette ".$id." remise en circulation";
                  }

                  //fin 1ere balise php?>

                  <form method="POST" action="">
                      <input class="btn btn-outline-dark mt-auto" type="submit" value="Lister les trottinettes qui ont besoin de revision ou réparation" name="submit_lister" > </input>
                  </form>

                  <form method="POST" action="">

                      <div class="form-group">

                          <input class="btn btn-outline-dark mt-auto" type="number"
                              id="id_trottinette"
                              placeholder="id trottinette" name="id_trottinette" required>

                          <input class="btn btn-outline-dark mt-auto" type="submit" value="Récuperer la trottinette" name="submit_recup_trot">
                          </input>
                      </div>
                  </form>

                  <br>

                  <form method="POST" action="">

                      <div class="form-group">

                          <input class="btn btn-outline-dark mt-auto" type="number"
                              id="id_trottinette"
                              placeholder="id trottinette" name="id_trottinette" required>

                          <input class="btn btn-outline-dark mt-auto" type="submit" value="Mettre la trottinette en service" name="submit_mise_service_trot">
                          </input>
                      </div>
                      <br>
                  </form>
                  <br>
                  <button class="btn btn-outline-dark mt-auto" onclick="location.href='app_gps_2points.php'">Consulter mes points de collecte du soir</button>

              </div>
          </div>
      </section>


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
}
include "footer.php";



?>
