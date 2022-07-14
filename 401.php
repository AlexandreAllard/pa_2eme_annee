<?php
session_start();
require "functions.php";
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
        <link rel="icon" type="image/x-icon" href="assets/Logo_orange.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/stylesFnny.css" rel="stylesheet" />


        <!-- css error -->
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="css/error/cssFnny.css" rel="stylesheet">
    </head>
    <body class="d-flex flex-column h-100">



  <main class="flex-shrink-0">
      <!-- Navigation-->
      <?php
      if(isset($_SESSION['statut']) ){
        if($_SESSION['statut'] == "collecteur"){
      		navbarCollector();
        }else{
          navbar();
        }
    	}else{
    		navbar();
    	} ?>
      <!-- Header-->

      <!-- Begin Page Content -->
      <div class="container-fluid">

          <!-- 403 Error Text -->
          <div class="text-center">
              <div class="error mx-auto" data-text="401">401</div>
              <p class="lead text-gray-800 mb-5">Vous n'êtes pas connecté</p>
              <p class="text-gray-500 mb-0">Cette page nécessite que vous soyez connecté. Rendez-vous <a href="login.php">ici</a> !</p>
              <p class="text-gray-500 mb-0">Vous êtes un collecteur ? Rendez-vous <a href="login_collector.php">ici</a> !</p>
              <a href="index.php">&larr; Revenir à l'accueil</a>
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
