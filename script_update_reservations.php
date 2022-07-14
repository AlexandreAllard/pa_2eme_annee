<?php
session_start();
//Factorisation
require "functions.php";

date_default_timezone_set('Europe/Paris');
$currentDate = date('Y-m-d H:i:s');
$timestamp2 = strtotime($currentDate);

$connection = connectDB();
$queryPrepared = $connection->prepare("SELECT * FROM caf_reservation");
$queryPrepared->execute();

// traiter forfaits expirés
while($reservationInfos = $queryPrepared->fetch()){
  $reservationId = $reservationInfos['id_reservation'];
  $reservationExpireDate = $reservationInfos['date_expiration'];

  $timestamp1 = strtotime($reservationExpireDate);

  if($timestamp1 <= $timestamp2){
    //etat
    $connection = connectDB();
    $reqOneReservation = $connection->prepare("UPDATE caf_reservation SET etat_reservation=0 WHERE id_reservation=:id_reservation");
    $reqOneReservation->execute(["id_reservation"=>$reservationId]);
  }
}

?>
