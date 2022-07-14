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
<section class="py-5 bg-light">
    <div class="container px-5 my-5">
        <div class="text-center">
            <h2 class="fw-bolder">Votre météo</h2>
            <p class="lead fw-normal text-muted mb-5">Actuellement, à Lyon...</p>

            <?php
              date_default_timezone_set('Europe/Paris');
              $date = date('d-m-y H:i:s');
              $hourParis = date('H');

              $connection = connectDB();
              $hourBDD = $connection->prepare("SELECT Max(heure) as currentHour from caf_meteo WHERE (((heure)<=:heure))");
              $hourBDD->execute(["heure"=>$hourParis]);
              $currentHour = $hourBDD->fetch();
              $hourOfReference = $currentHour['currentHour'];

              $connection = connectDB();
              $hourBDDMeteo = $connection->prepare("SELECT * from caf_meteo WHERE heure=:heure");
              $hourBDDMeteo->execute(["heure"=>$hourOfReference]);
              $currentHour = $hourBDDMeteo->fetch();

              $currentTimeState = translateMeteo($currentHour['description']);

             ?>
             <img class="img-fluid" src="meteo/<?php echo $currentHour['description']; ?>.png" alt="..." height="150" width="150"/>
             <b><p class="lead fw-normal mb-5"><?php echo $currentTimeState; ?></p></b>
             <p class="lead fw-normal mb-5"><?php echo $currentHour['temperature']; ?>°C</p>
        </div>
        <div class="text-center">
            <h2 class="fw-bolder">Les prévisions du jour</h2>
            <p class="lead fw-normal text-muted mb-5">Pour savoir si vous prenez votre KWAY ou vos lunettes de soleil...</p>
            <?php
              $connection = connectDB();
              $meteos = $connection->prepare("SELECT * FROM caf_meteo ORDER BY heure ASC");
              $meteos->execute();
             ?>
        </div>
        <div class="row gx-5 row-cols-1 row-cols-sm-2 row-cols-xl-4 justify-content-center">
            <?php
            while($meteo = $meteos->fetch()) {
                $HourMeteo = $meteo['heure'];
                $TempMeteo = $meteo['temperature'];
                $DescriptionMeteo = translateMeteo($meteo['description']); ?>
            <div class="col mb-5 mb-5 mb-xl-0">
                <div class="text-center">
                    <h5 class="fw-bolder"><?php echo $HourMeteo;?>h</h5>
                    <img class="img-fluid mb-4 px-4" src="meteo/<?php echo $meteo['description']; ?>.png" alt="..." height="150" width="150"/>
                    <h5 class="fw-bolder"><?php echo $DescriptionMeteo;?></h5>
                    <div class="fst-italic text-muted"><?php echo $TempMeteo;?>°C</div><br><br>
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
