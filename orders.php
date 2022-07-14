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
                          <h1 class="fw-bolder mb-3">Mes commandes</h1>
                      </div>
                  </div>
              </div>
          </div>
      </header>

      <!-- Features section-->
      <section class="py-5" id="features">
          <div class="container px-5 my-5">
              <div class="row gx-5">
                  <div class="col-lg-4 mb-5 mb-lg-0"><h2 class="fw-bolder mb-0">Historique de mes commandes</h2></div>
                  <div class="col-lg-8">
                      <div class="row gx-5 row-cols-1 row-cols-md-2">

                        <?php
                             $connection = connectDB();
                             $id_client = $_SESSION["id"];
                             // la requête
                             $req = $connection->prepare("SELECT * FROM caf_commande WHERE id_client=:id_client");
                             $req->execute(["id_client"=>$id_client]);

                             if ($req->rowCount() > 0) {

                               while($infos_order = $req->fetch()){
                                  $orderId = $infos_order['numero_de_commande'];
                                  $orderType = ucwords(mb_strtolower(trim($infos_order['type_commande'])));
                                  $orderPrice = $infos_order['montant_commande'];
                                  $orderDate = $infos_order['date_commande'];
                                  $orderDeliveryDate = $infos_order['date_livraison'];
                                  $orderProductName = $infos_order['nom_produit'];
                                  $fidelityPointsUsed = $infos_order['point_fidelite_utilise'];
                                  ?>
                                  <div class="col mb-2 h-20">
                                      <h2 class="h5" style="color:#FF8A00;">Commande n°<b><?php echo $orderId?></b> - <?php echo $orderPrice?>€</h2>
                                      <h3 class="h6">Effectuée le : <?php echo $orderDate?></h3>
                                      <h3 class="h6">Date de livraison estimée : <?php echo $orderDeliveryDate?></h3>
                                      <?php if($fidelityPointsUsed != 0){ ?>
                                        <p class="mb-0">Points de fidélité utilisés : <b><?php echo $fidelityPointsUsed?></b></p>
                                      <?php } ?>
                                      <p class="mb-0"><?php echo $orderType ?> commandé : <b><?php echo $orderProductName ?></b></p>
                                      <br><br>
                                  </div>
                                <?php
                              }
                            } else { ?>
                              <div class="col mb-2 h-20">
                                <h2 class="h5" style="color:#FF8A00;">Aucune commande n'a été effectuée pour le moment !</h2>
                                <p class="mb-0">Jetez donc un oeil à notre <b><a href="shop.php">boutique</a></b> !</p>
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
