<?php
/*
APP pour calculer l'itineraire entre 2 coordonnées
exemple :
https://maps.googleapis.com/maps/api/distancematrix/json?destinations=45.773758%2C4.818424&origins=45.775126%2C4.824298&key=AIzaSyDcDc_KW8bV7fI_zFapOTEDjlZA9tW3Y00
45.773758%2C4.818424 : ici 2% = séparateur des coordonnées
*/

require "conf.php";
require "functions.php";

$connection = connectDB();

/*
$lat_collecteur = $connection->prepare('SELECT `latitude` FROM `caf_collecteur` WHERE id_collecteur=1');
$lat_collecteur->execute();


$long_collecteur = $connection->prepare('SELECT `longitude` FROM `caf_collecteur` WHERE id_collecteur=1');
$long_collecteur->execute();


$lat_point_collecte = $connection->prepare('SELECT latitude FROM caf_point_collecte WHERE id_point_collecte=2');
$lat_point_collecte->execute();

$long_point_collecte = $connection->prepare('SELECT `longitude` FROM `caf_point_collecte` WHERE id_point_collecte=2');
$long_point_collecte->execute();

*/


$collecteurs = $connection->query('SELECT * FROM caf_collecteur');
$points_collecte = $connection->query('SELECT * FROM caf_point_collecte');




while ($point_collecte = $points_collecte->fetch()) {

  while($collecteur = $collecteurs->fetch()) {
      ?><li><?php

          echo $collecteur['id_collecteur'].":";
          echo $collecteur['nom'];
          echo $collecteur['prenom'];
          echo $collecteur['latitude'];
          echo $collecteur['longitude'];
          ?><hr></li>
      <?php



      $lat_collecteur = $collecteur['latitude'];
      $long_collecteur =$collecteur['longitude'];


      $url_start = "https://maps.googleapis.com/maps/api/distancematrix/";
      $url_datatype="json";

      $url_dest="?destinations=";
      $url_dest_lat=$lat_collecteur;
      $url_separateur="%2C";
      $url_dest_long=$long_collecteur;

      $url_origine="&origins=";



      $url_end="&key=AIzaSyDcDc_KW8bV7fI_zFapOTEDjlZA9tW3Y00";



      $lat_point_collecte =$point_collecte['latitude'];
      $long_point_collecte =$point_collecte['longitude'];

      $url_origine_lat=$lat_point_collecte;
      $url_separateur2="%2C";
      $url_origine_long=$long_point_collecte;

      $url_final=$url_start.$url_datatype.$url_dest.$url_dest_lat.$url_separateur.$url_dest_long.$url_origine.$url_origine_lat.$url_separateur2.$url_origine_long.$url_end;




      ?><br><?php
      echo $url_final;
      ?><br><?php
   }







}
