<?php
session_start();
//Factorisation
include "header.php";
require "functions.php";

if(!isset($_SESSION['statut']) || !isset($_SESSION['prenom'])) {

    header("Location: 401.php");

} else {

  if ( count($_POST) > 1
  	&& !empty($_POST["id_reservation"])
  	&& !empty($_POST["id_trottinette"])
  ) {

  	$reservationId = $_POST["id_reservation"];
  	$scooterId = NULL;
    //$planId = $_POST["id_forfait"];

    $connection = connectDB();
    $reqCheckReservation = $connection->prepare("SELECT nb_courses FROM caf_reservation WHERE id_reservation=:id_reservation");
    $reqCheckReservation->execute( ["id_reservation"=>$reservationId] );
    $resultCheck = $reqCheckReservation->fetch();
    if($resultCheck["nb_courses"] == 0){
      $reservationStateInt = 0;
    }else{
      $reservationStateInt = 1;
    }

    //définition heure fin
    date_default_timezone_set('Europe/Paris');
    $reservationEndHour = date('Y-m-d H:i:s');

    $connection = connectDB();
    $reqStopReservation = $connection->prepare("UPDATE caf_reservation SET etat_reservation=:etat_reservation, heure_fin=:heure_fin, id_trottinette=:id_trottinette WHERE id_reservation=:id_reservation");
		$reqStopReservation->execute( ["etat_reservation"=>$reservationStateInt, "heure_fin"=>$reservationEndHour,"id_trottinette"=>$scooterId,"id_reservation"=>$reservationId] );


    header("Location: reservations.php");
    /*
    $reservationId = $infos_reservation['id_reservation'];
    $reservationStateInt = $infos_reservation['etat_reservation'];
    $reservationPrice = $infos_reservation['prix_reservation'];
    $reservationDuration = $infos_reservation['duree_reservation'];
    $reservationMakeDate = $infos_reservation['date_reservation'];
    $reservationExpireDate = $infos_reservation['date_expiration'];
    $reservationNbCourses = $infos_reservation['nb_courses'];
    $reservationBeginHour =$infos_reservation['heure_debut'];
    $reservationEndHour = $infos_reservation['heure_fin'];
    $scooterId = $infos_reservation['id_trottinette'];
    */

  }else{
    echo "Une erreur est survenue. Veuillez réessayer plus tard";
  }
}
?>
