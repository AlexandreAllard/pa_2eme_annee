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
      <header class="bg-dark py-5">
          <div class="container px-5">
              <div class="row gx-5 align-items-center justify-content-center">
                  <div class="col-lg-8 col-xl-7 col-xxl-6">
                      <div class="my-5 text-center text-xl-start">
                          <h1 class="display-5 fw-bolder text-white mb-2">La liberté à portée de main</h1>
                          <p class="lead fw-normal text-white-50 mb-4">Redécouvrez le plaisir de vous balader.</p>
                          <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                              <a class="btn btn-primary btn-lg px-4 me-sm-3" href="pricing.php">Tarifs</a>
                              <a class="btn btn-outline-light btn-lg px-4" href="shop.php">Boutique</a>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center">
                    <a alt="WEBGL" href="three.js-master/examples/PA.html">
                    <img class="img-fluid rounded-3 my-5" src="./assets/projetA.png" alt="..."  width="200" height="200" /></div>
                  </a>
              </div>
          </div>
      </header>
      <!-- Features section-->
      <section class="py-5" id="features">
          <div class="container px-5 my-5">
              <div class="row gx-5">
                  <div class="col-lg-4 mb-5 mb-lg-0"><h2 class="fw-bolder mb-0">Prendre du plaisir à se déplacer? C'est possible!</h2></div>
                  <div class="col-lg-8">
                      <div class="row gx-5 row-cols-1 row-cols-md-2">
                          <div class="col mb-5 h-100">
                              <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-collection"></i></div>
                              <h2 class="h5">Qualité</h2>
                              <p class="mb-0">Une flotte de trotinettes récentes et entretenues au jour le jour.</p>
                          </div>
                          <div class="col mb-5 h-100">
                              <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-building"></i></div>
                              <h2 class="h5">Récompense</h2>
                              <p class="mb-0">Votre fidélité est récompensée, accumulez des avantages au fil des trajets.</p>
                          </div>
                          <div class="col mb-5 mb-md-0 h-100">
                              <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-toggles2"></i></div>
                              <h2 class="h5">Flexibilité</h2>
                              <p class="mb-0">Avec nos différents forfaits, ne payez que ce dont vous avez besoin.</p>
                          </div>
                          <div class="col h-100">
                              <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-toggles2"></i></div>
                              <h2 class="h5">Disponibilité</h2>
                              <p class="mb-0">Déployées sur toute la ville, nous vous garantissons de pouvoir trouver une trotinette en moins de 5mn.</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <!-- Testimonial section-->
      <div class="py-5 bg-light">
          <div class="container px-5 my-5">
              <div class="row gx-5 justify-content-center">
                  <div class="col-lg-10 col-xl-7">
                      <div class="text-center">
                          <div class="fs-4 mb-4 fst-italic">"Notre engagement: vous rendre votre liberté, celle de vous déplacer, mais aussi celle d'apprécier la ville qui vous entoure."</div>
                          <div class="d-flex align-items-center justify-content-center">
                              <img class="rounded-circle me-3" src="./assets/projetA.png"  width="40" height="40" alt="..." />
                              <div class="fw-bold">
                                  <span class="fw-bold text-primary mx-1">/</span>
                                  L'équipe Trotterfly
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- Blog preview section-->
      <section class="py-5">
          <div class="container px-5 my-5">
              <div class="row gx-5 justify-content-center">
                  <div class="col-lg-8 col-xl-6">
                      <div class="text-center">
                          <h2 class="fw-bolder">Nos accessoires</h2>
                          <p class="lead fw-normal text-muted mb-5">Notre offre ne se limite pas à la location de trotinettes. Vous trouverez ici tout ce qu'il faut pour vivre pleinement votre expérience</p>
                      </div>
                  </div>
              </div>
              <div class="row gx-5">
                <?php
                    $connection = connectDB();

                    //connaitre le nombre de produits dans la BDD
                    $countIds = $connection->prepare('SELECT COUNT(*) as IdProducts FROM caf_produit');
                    $countIds->execute();
                    $donnees = $countIds->fetch();
                    $countIds->closeCursor();
                    $numberProducts = $donnees['IdProducts']; //exemple : 5

                    //connaitre l'id le plus grand
                    $maxId = $connection->prepare('SELECT MAX(id_produit) as IdProduct FROM caf_produit');
                    $maxId->execute();
                    $donnee = $maxId->fetch();
                    $maxId->closeCursor();
                    $maxIdProducts = $donnee['IdProduct'];
                    //echo $maxIdProducts; //exemple : 10

                    //lister les IDs des produits qui existent dans la BDD
                    $arrayIds = array();
                    $listIds = $connection->prepare('SELECT id_produit FROM caf_produit');
                    $listIds->execute();
                    $allIds = $listIds->fetchAll(PDO::FETCH_COLUMN,0);
                    foreach ($allIds as $row => $value) {
                      //echo $value;
                      // on stock tous les ID qui existent dans un tableau.
                      array_push($arrayIds, $value);
                    }
                    ?> <br><?php
                    //jusqu'ici tout va bien

                    $counter = 0;
                    $idAlreadyChosen = array();
                    while($counter != 3){
                        $random = 0;
                        do{
                            $random = rand(1,$maxIdProducts);
                            while(in_array($random, $idAlreadyChosen)){
                              $random = rand(1,$maxIdProducts);
                            }
                          }while(!in_array($random, $arrayIds));

                          $idAlreadyChosen[$counter] = $random;
                          $counter += 1 ;

                          $produit = $connection->prepare('SELECT * FROM caf_produit WHERE id_produit=:id_produit');
                          $produit->execute(["id_produit"=>$random]);
                          $details = $produit->fetch();

                          $prix_produit = $details['prix_produit'];
                          $nom_produit = $details['nom_produit'];
                          $description_produit = $details['description_produit'];
                          $photo_produit = $details['photo_produit'];
                          $id_categorie_produit = $details['id_categorie'];

                          $categorie = $connection->prepare('SELECT * FROM caf_categorie_produit WHERE id_categorie=:id_categorie');
                          $categorie->execute(["id_categorie"=>$id_categorie_produit]);
                          $detailsCategorie = $categorie->fetch();
                          $categorie_produit = $detailsCategorie['nom_categorie'];

                          ?>

                          <!--3 produits au hasard -->
                          <div class="col-lg-4 mb-5">
                              <div class="card h-100 shadow border-0">
                                  <?php echo '<img class="card-img-top" src="' . $photo_produit .'" alt="..." width="600" height="350" >'?>
                                  <div class="card-body p-4">
                                      <div class="badge bg-primary bg-gradient rounded-pill mb-2"><?php echo $categorie_produit ?></div>
                                      <a class="text-decoration-none link-dark stretched-link" href="#!"><h5 class="card-title mb-3"><?php echo $nom_produit ?></h5></a>
                                      <p class="card-text mb-0"><?php echo $description_produit ?></p>
                                  </div>
                                  <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                      <div class="d-flex align-items-end justify-content-between">
                                          <div class="d-flex align-items-center">
                                              <div class="small">
                                                  <div class="fw-bold"><?php echo $prix_produit ?> €</div>
                                                  <div class="text-muted">A découvrir</div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <?php
                    }

                    ?>
              </div>
              <!-- Call to action-->
              <aside class="bg-primary bg-gradient rounded-3 p-4 p-sm-5 mt-5">
                  <div class="d-flex align-items-center justify-content-between flex-column flex-xl-row text-center text-xl-start">
                      <div class="mb-4 mb-xl-0">
                          <div class="fs-3 fw-bold text-white">Restez à l'affût de nos dernières promotions.</div>
                          <div class="text-white-50">Inscrivez-vous à notre Newsletter.</div>
                      </div>
                      <div class="ms-xl-4">
                          <div class="input-group mb-2">
                              <input class="form-control" type="text" placeholder="Adresse mail..." aria-label="Adresse mail..." aria-describedby="button-newsletter" />
                              <button class="btn btn-outline-light" id="button-newsletter" type="button">Inscription</button>
                          </div>
                      </div>
                  </div>
              </aside>
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
