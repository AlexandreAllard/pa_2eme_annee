<?php
session_start();
//Factorisation
include "header.php";
require "functions.php";
?>

  <main class="flex-shrink-0">
      <!-- Navigation-->
      <?php navbar(); ?>
        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Équipez vous pour partir à l'aventure</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Ces produits choisis par notre équipe sont faits pour vous!</p>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">


                  <?php
                  $connection = connectDB();
                  $produits = $connection->query('SELECT * FROM caf_produit ORDER BY id_produit DESC LIMIT 30');
                  while($produit = $produits->fetch()) { ?>

                    <?php $photo_produit = $produit['photo_produit'];
                    //echo $produit['description_produit'] ?>

                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <?php echo '<img class="card-img-top" src="' . $photo_produit .'" alt="..." width="450" height="300" >'?>
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder"><?php echo $produit['nom_produit'] ?></h5>
                                    <!-- Product price-->
                                    <?php echo $produit['prix_produit']?>€
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Sélectionner</a></div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>


                    <!-- save pour éventuelles promos
                    <div class="col mb-5">
                        <div class="card h-100">
                            <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                            <img class="card-img-top" src="./assets/casque.jpg" alt="..."  width="450" height="300" />
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h5 class="fw-bolder">Casque</h5>
                                    <div class="d-flex justify-content-center small text-warning mb-2">
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                    </div>
                                    <span class="text-muted text-decoration-line-through">20.00€</span>
                                    29.99€
                                </div>
                            </div>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Sélectionner</a></div>
                            </div>
                        </div>
                    </div>
                  -->
                </div>
            </div>
        </section>

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

<?php include "footer.php"; ?>
