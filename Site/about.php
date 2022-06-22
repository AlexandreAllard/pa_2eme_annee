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
            <header class="py-5">
                <div class="container px-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-xxl-6">
                            <div class="text-center my-5">
                                <h1 class="fw-bolder mb-3">Ce que nous offrons? Une expérience, le goût de la liberté allié à la simplicité.</h1>
                                <p class="lead fw-normal text-muted mb-4">Louez une trotinnette à tout moment, en moins d'une minute et à un prix réduit, qui s'adapte à vos besoins et à votre utilisation. Trop beau pour être vrai? Non, juste Trotterfly.</p>
                                <a class="btn btn-primary btn-lg" href="#scroll-target">Découvrez-nous</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- About section one-->
            <section class="py-5 bg-light" id="scroll-target">
                <div class="container px-5 my-5">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-6"><img class="img-fluid rounded mb-5 mb-lg-0" src="./assets/lyon1.jpg" alt="..." /></div>
                        <div class="col-lg-6">
                            <h2 class="fw-bolder">Les prémices</h2>
                            <p class="lead fw-normal text-muted mb-0">Fondée en 2016, nous sommes une des premières entreprises françaises à proposer la location de Trotinnettes en libre-service et la première à Lyon.</p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- About section two-->
            <section class="py-5">
                <div class="container px-5 my-5">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-6 order-first order-lg-last"><img class="img-fluid rounded mb-5 mb-lg-0" src="./assets/lyon2.jpg" alt="..." /></div>
                        <div class="col-lg-6">
                            <h2 class="fw-bolder">Notre engagement</h2>
                            <p class="lead fw-normal text-muted mb-0">Nous nous engageons à vous fournir des équipements de bonne qualité, révisés et qui vous permettrons de circuler en toute sécurité.</p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Team members section-->
            <section class="py-5 bg-light">
                <div class="container px-5 my-5">
                    <div class="text-center">
                        <h2 class="fw-bolder">Notre équipe</h2>
                        <p class="lead fw-normal text-muted mb-5">Communément appelés les trois mousquetaires.</p>
                    </div>
                    <div class="row gx-5 row-cols-1 row-cols-sm-2 row-cols-xl-4 justify-content-center">
                        <div class="col mb-5 mb-5 mb-xl-0">
                            <div class="text-center">
                                <img class="img-fluid rounded-circle mb-4 px-4" src="./assets/kid3.jpg" alt="..." />
                                <h5 class="fw-bolder">ALLARD Alexandre</h5>
                                <div class="fst-italic text-muted">CTO</div>
                            </div>
                        </div>
                        <div class="col mb-5 mb-5 mb-xl-0">
                            <div class="text-center">
                                <img class="img-fluid rounded-circle mb-4 px-4" src="./assets/kid2.jpg" alt="..." />
                                <h5 class="fw-bolder">GUILLEMONT Fanny</h5>
                                <div class="fst-italic text-muted">CEO</div>
                            </div>
                        </div>
                        <div class="col mb-5 mb-5 mb-sm-0">
                            <div class="text-center">
                                <img class="img-fluid rounded-circle mb-4 px-4" src="./assets/kid1.jpg" alt="..." />
                                <h5 class="fw-bolder">LACOUTURE Camille</h5>
                                <div class="fst-italic text-muted">Operations Manager</div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </main>
        <!-- Footer-->
        <?php
  include "footer.php";
?>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
