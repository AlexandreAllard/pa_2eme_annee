<?php
session_start();
//Factorisation
include "header.php";
require "functions.php";

if(!isset($_SESSION['statut']) || !isset($_SESSION['prenom'])) {

    header("Location: 401.php");


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
    	}  ?>
      <!-- Header-->
      <header class="py-5">
          <div class="container px-5">
              <div class="row justify-content-center">
                  <div class="col-lg-8 col-xxl-6">
                      <div class="text-center my-5">
                          <h1 class="fw-bolder mb-3">Mes réservations</h1>
                      </div>
                  </div>
              </div>
          </div>
      </header>

      <!-- Features section-->
      <section class="py-5" id="features">
          <div class="container px-5 my-5">
              <div class="row gx-5">
                  <div class="col-lg-4 mb-5 mb-lg-0"><h2 class="fw-bolder mb-0">Réservations effectuées</h2></div>
                  <div class="col-lg-8">
                      <div class="row gx-5 row-cols-1 row-cols-md-2">

                        <?php
                             $connection = connectDB();
                             $id_client = $_SESSION["id"];
                             // la requête
                             $req = $connection->prepare("SELECT * FROM caf_reservation WHERE id_client=:id_client ORDER BY id_reservation DESC");
                             $req->execute(["id_client"=>$id_client]);

                             if ($req->rowCount() > 0) {

                               while($infos_reservation = $req->fetch()){
                                  $reservationId = $infos_reservation['id_reservation'];
                                  $reservationStateInt = $infos_reservation['etat_reservation'];
                                  $reservationPrice = $infos_reservation['prix_reservation'];
                                  $reservationDuration = $infos_reservation['duree_reservation'];
                                  $reservationMakeDate = $infos_reservation['date_reservation'];
                                  $reservationExpireDate = $infos_reservation['date_expiration'];
                                  $reservationNbCourses = $infos_reservation['nb_courses'];
                                  $reservationBeginHour =$infos_reservation['heure_debut'];
                                  $reservationEndHour = $infos_reservation['heure_fin'];
                                  $planId = $infos_reservation['id_forfait'];
                                  $scooterId = $infos_reservation['id_trottinette'];

                                  //Traitement date du jour - date d'expiration
                                  $date1 = $reservationExpireDate;

                                  if($reservationStateInt!=2){
                                    date_default_timezone_set('Europe/Paris');
                                    $date2 = date('Y-m-d H:i:s');
                                    $timestamp1 = strtotime($date1);
                                    $timestamp2 = strtotime($date2);

                                    // Vérification forfait
                                    $req_search_plan= $connection->prepare("SELECT * FROM caf_forfait WHERE id_forfait=:id_forfait");
                                    $req_search_plan->execute(["id_forfait"=>$planId]);
                                    $planInfos = $req_search_plan->fetch();
                                    $planNbCourses = $planInfos['nb_courses'];

                                    if($planNbCourses != NULL){
                                      if($reservationNbCourses==0 || $timestamp1 <= $timestamp2){
                                        $reservationStateInt=0;
                                      }else{
                                        $reservationStateInt=1;
                                      }
                                    }else{
                                      if($timestamp1 <= $timestamp2){
                                        $reservationStateInt=0;
                                      }else{
                                        $reservationStateInt=1;
                                      }
                                    }

                                  }
                                  if($reservationStateInt==0){
                                    $reservationStateString="Epuisée";
                                  }else if($reservationStateInt==2){
                                    $reservationStateString="En cours";
                                  }else{
                                    $reservationStateString="Valable";
                                  }

                                  ?>
                                  <div class="col mb-2 h-20">
                                      <h2 class="h5" style="color:#FF8A00;">Réservation n°<b><?php echo $reservationId?></b> - <?php echo $reservationPrice?>€</h2>
                                      <h2 class="h5">Etat : <b><?php echo $reservationStateString?></b></h2>
                                      <h3 class="h6">Effectuée le : <?php echo $reservationMakeDate?></h3>
                                      <h3 class="h6">Date d'expiration : <?php echo $reservationExpireDate?></h3>
                                      <p class="mb-0">Nombres de courses restantes : <b><?php echo $reservationNbCourses?></b></p>
                                      <p class="mb-0">Temps de trajet accordé par course : <b><?php echo $reservationDuration?></b></p>
                                      <p class="mb-0">Heure de début : <b><?php echo $reservationBeginHour?></b><br>Heure de fin : <b><?php echo $reservationEndHour?></b></p>
                                      <p class="mb-0">Numéro de trottinette : <b><?php echo $scooterId ?></b></p>

                                      <?php if($reservationStateInt==1){ ?>
                                      <form method="POST" action="active_course.php">
                                        <input type="hidden" id="id_reservation" name="id_reservation" value="<?php echo $reservationId?>" required="required">
                                        <input type="hidden" id="id_forfait" name="id_forfait" value="<?php echo $planId ?>" required="required">
                                        <br>
                                        <input type="number" id="id_trottinette" name="id_trottinette" placeholder="ID trottinette" required="required">
                                        <br>
                                        <input type="submit" name="submit_active_course" value="Rendre une course active">
                                        </input>
                                      </form>
                                      <?php } ?>
                                      <?php if($reservationStateInt==2){ ?>
                                      <form method="POST" action="desactive_course.php">
                                        <input type="hidden" id="id_reservation" name="id_reservation" value="<?php echo $reservationId?>" required="required">
                                        <input type="hidden" id="id_trottinette" name="id_trottinette" value="<?php echo $scooterId?>" required="required">
                                        <br>
                                        <input type="submit" name="submit_desactive_course" value="Arrêter la course">
                                        </input>
                                      </form>
                                      <?php } ?>
                                      <br><br>
                                  </div>
                                <?php
                              }
                            } else { ?>
                              <div class="col mb-2 h-20">
                                <h2 class="h5" style="color:#FF8A00;">Aucune réservation n'a été effectuée pour le moment !</h2>
                                <p class="mb-0">Jetez donc un oeil à nos <b><a href="pricing.php">offres et tarifs !</a></b> !</p>
                              </div>
                            <?php } ?>

                      </div>
                  </div>
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
