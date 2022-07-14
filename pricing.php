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
        <!-- Pricing section-->
        <section class="bg-light py-5">
            <div class="container px-5 my-5">
                <div class="text-center mb-5">
                    <h1 class="fw-bolder">Ne payez que ce dont vous avez besoin</h1>
                    <p class="lead fw-normal text-muted mb-0">Nos tarifs s'adaptent à vous</p>
                </div>
                <div class="row gx-5 justify-content-center">


                  <?php
                  $connection = connectDB();
                  $forfaits = $connection->query('SELECT * FROM caf_forfait ORDER BY id_forfait ASC LIMIT 30');
                  while($forfait = $forfaits->fetch()) {

                    $forfaitType = ucwords(mb_strtolower(trim($forfait["type_forfait"])));


                    if($forfait["prix_minute"]!= NULL){
                      $forfaitPrice = $forfait["prix_minute"];
                      $forfaitTypePer = "minute";
                      $forfaitType = "A la minute";
                    } else if($forfait["prix_jour"]!= NULL){
                      $forfaitPrice = $forfait["prix_jour"];
                      $forfaitTypePer = "jour";

                    } else if($forfait["prix_mois"]!= NULL){
                      $forfaitPrice = $forfait["prix_mois"];
                      $forfaitTypePer = "mois";
                    } else if($forfait["prix_an"]!= NULL){
                      $forfaitPrice = $forfait["prix_an"];
                      $forfaitTypePer = "an";
                    }

                    if($forfait["prix_deverrouillage"]==0){
                      $unlockInfos = "Frais de déblocage offerts !";
                    }else{
                      $unlockInfos = $forfait["prix_deverrouillage"] . "€ de frais de déverrouillage";
                    }


                    if($forfait["nb_courses"]!=0){
                      $coursesNumber = $forfait["nb_courses"] . " course(s) de " . $forfait["temps_course"] . " minutes";
                    }else if($forfait["temps_course"] != 0){
                      $coursesNumber = $forfait["temps_course"] . " minutes de trajet disponibles";
                    }else{
                      $coursesNumber = "Roulez autant que vous le désirez";
                    }

                    if($forfait["temps_expiration_heures"]!=0){
                      $days = $forfait["temps_expiration_heures"] / 24 ;
                      $expiryInfos = "Expiration au bout de " . $days . " jour(s)";
                    }else{
                      $expiryInfos = "Pas d'expiration";
                    }

                    ?>


                    <div class="col-lg-6 col-xl-4">
                        <div class="card mb-5 mb-xl-0">
                            <div class="card-body p-5">
                                <div class="small text-uppercase fw-bold text-muted"><?php echo $forfait['nom_forfait']; ?></div>
                                <div class="mb-3">
                                    <span class="display-4 fw-bold"><?php echo $forfaitPrice; ?>€</span>
                                    <span class="text-muted">/ <?php echo $forfaitTypePer ?>.</span>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        <strong><?php echo $forfaitType ?></strong>
                                    </li>

                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        <?php //Infos sur le prix de déblocage de la trottinette
                                        echo $unlockInfos ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        <?php //Infos sur le nombre de courses et leur durée
                                        echo $coursesNumber ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-primary"></i>
                                        <?php //Infos sur l'expiration du forfait
                                        echo $expiryInfos ?>
                                    </li>
                                </ul>

                                <?php
                                //connecté? formulaire de paiement sinon login.php
                                 if(isset($_SESSION['prenom'])) {?>
                                <form method="post" action="./Stripe/achat_forfait.php">
                                  <?php echo '<div class="d-grid"><button class="btn btn-outline-primary" value="'.$forfait['id_forfait'].'" name="id_forfait_select" type="submit">Choisir ce forfait</button></div>'  ?>
                                </form>
                                <?php }else { ?>
                                  <form method="post" action="login.php">
                                    <?php echo '<div class="d-grid"><button class="btn btn-outline-primary" type="submit">Choisir ce forfait</button></div>'  ?>
                                    </form>
                                <?php } ?>


                            </div>
                        </div>
                    </div>
                    <?php } ?>
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
include "footer.php";
?>
