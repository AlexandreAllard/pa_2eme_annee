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


} else { ?>

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
                            <h1 class="fw-bolder mb-3">Mes points de collecte</h1>
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


                    $connection = connectDB();

                    $collecteurs = $connection->prepare('SELECT * FROM caf_collecteur');
                    $collecteurs->execute();
                    $points_collectes = $connection->prepare('SELECT * FROM caf_point_collecte');
                    $points_collectes->execute();



                    // CONSTANTE pour completer l'url API maps
                    $url_start = "https://maps.googleapis.com/maps/api/distancematrix/";
                    $url_datatype="json";

                    $url_dest="?destinations=";
                    //$url_dest_lat=$lat_collecteur;
                    $url_separateur="%2C";
                    //$url_dest_long=$long_collecteur;

                    $url_origine="&origins=";
                    $url_end="&key=AIzaSyDcDc_KW8bV7fI_zFapOTEDjlZA9tW3Y00";


                    // Foreach dans foreach ne fonctionne pas dans notre cas
                    // Solution : stocker chaque foreach dans un tableau : tab_point_collecte & tab_collecteur

                    // POINT COLLECTE => ARRAY
                    $tab_point_collecte=array();
                    $lengthPointCollecte=0;
                    foreach ($points_collectes as $point_collecte) {
                        $newline = ['id_point_collecte' => $point_collecte['id_point_collecte'],
                                    'numero_rue' => $point_collecte['numero_rue'],
                                    'rue' => $point_collecte['rue'],
                                    'ville' => $point_collecte['ville'],
                                    'latitude' => $point_collecte['latitude'],
                                    'longitude' => $point_collecte['longitude']
                                    ];

                        array_push($tab_point_collecte, $newline);
                        $lengthPointCollecte++;
                    }

                    // COLLECTEUR => ARRAY
                    $tab_collecteur=array();
                    $lengthCollecteur=0;
                      foreach ($collecteurs as $collecteur) {
                        $newline = ['id_collecteur' => $collecteur['id_collecteur'],
                                    'nom' => $collecteur['nom'],
                                    'prenom' => $collecteur['prenom'],
                                    'latitude' => $collecteur['latitude'],
                                    'longitude' => $collecteur['longitude'],
                                    'dispo' => 1
                                    ];

                        array_push($tab_collecteur, $newline);
                        $lengthCollecteur++;
                    }


                    // Point de collecte max pour un collecteur
                    $ptCollecteMax=$lengthPointCollecte%$lengthCollecteur+1;
                    for ($i=0; $i < $lengthCollecteur; $i++) {
                      $tab_collecteur[$i]['dispo']=$ptCollecteMax;
                    }

                    // AFFICHAGE ARRAYS
                    /*
                    for ($i=0; $i < $lengthPointCollecte; $i++) {
                      for ($j=0; $j < $lengthCollecteur; $j++) {
                      echo $tab_point_collecte[$i]['id_point_collecte'];
                      echo $tab_point_collecte[$i]['numero_rue'];
                      echo $tab_point_collecte[$i]['rue'];
                      echo $tab_point_collecte[$i]['ville'];
                      echo $tab_point_collecte[$i]['latitude'];
                      echo $tab_point_collecte[$i]['longitude'];
                      echo "<br>";
                      echo $tab_collecteur[$j]['id_collecteur'];
                      echo $tab_collecteur[$j]['nom'];
                      echo $tab_collecteur[$j]['prenom'];
                      echo $tab_collecteur[$j]['latitude'];
                      echo $tab_collecteur[$j]['longitude'];

                      echo "<hr>";
                      }
                    }
                    */



                    // TRAITEMENT COLLECTEUR/POINT COLLECTE
                    for ($i=0; $i < $lengthPointCollecte; $i++) {


                        $distMin=99999999;// Initialisation

                        for ($j=0; $j < $lengthCollecteur; $j++) {

                              // Récuperation des coordonnées
                              $url_dest_lat=$tab_point_collecte[$i]['latitude'];
                              $url_dest_long=$tab_point_collecte[$i]['longitude'];

                              $url_origine_lat=$tab_collecteur[$j]['latitude'];
                              $url_origine_long=$tab_collecteur[$j]['longitude'];

                              //url de l'api GPS
                              $url_final=$url_start.$url_datatype.$url_dest.$url_dest_lat.$url_separateur.$url_dest_long.$url_origine.$url_origine_lat.$url_separateur.$url_origine_long.$url_end;
                              //echo $url_final;


                          // On veut maintenant récuperer que la distance venant de l'url:
                          // JSON GET DISTANCE===============================================
                              $distance=getDistance($url_final);

                              // Collecteur dispo ? Est-il le plus proche du point de collecte?
                              if ($tab_collecteur[$j]['dispo']>0) {
                                  if ($distMin>$distance) {
                                      $distMin=(double) $distance; //distance du collecteur le plus proche
                                      $idCollecteurMin=$tab_collecteur[$j]['id_collecteur']; //id collecteur le plus proche, stockage dans var pour la réutiliser apres
                                  }
                              }

                        }// fin du for j

                    if ($idCollecteurMin==$_SESSION["id"]) {
                      // code...

                        echo  $tab_point_collecte[$i]['numero_rue']." ".//affichage du point de collecte concerné
                              $tab_point_collecte[$i]['rue']." ".
                              $tab_point_collecte[$i]['ville']."<br>" ;

                        // Boucler sur le tableau local marcherait aussi
                        // Récuperation des données du collecteur le plus proche
                        $req = $connection->prepare('SELECT * FROM caf_collecteur WHERE id_collecteur=:id_collecteur');
                        $req->execute(["id_collecteur"=>$idCollecteurMin]);
                        $collecteurLePlusProche = $req->fetch();

                        echo "<br>" . $collecteurLePlusProche['prenom'] .", vous devez vous rendre à ce point de collecte | ".$distMin." km ";
                        echo "<hr>";


                      }




                      //passer le collecteur en dispo -= 1
                      for ($k=0; $k < $lengthCollecteur; $k++) {
                          //echo "<br>";
                          //echo $tab_collecteur[$k]['id_collecteur']." et ".$idCollecteurMin;
                          if($tab_collecteur[$k]['id_collecteur']==$idCollecteurMin){
                              $tab_collecteur[$k]['dispo']-=1;
                              //echo "<br>".$tab_collecteur[$k]['nom']." dispo restant ".$tab_collecteur[$k]['dispo'];
                          }

                      }


                    }//fin for j

                    ?>
                    <button class="btn btn-outline-dark mt-auto" onclick="location.href='app_collector.php'">Revenir à l'application Collecteur</button>

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
