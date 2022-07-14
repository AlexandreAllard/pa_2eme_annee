<?php

session_start();


if(!isset($_SESSION['statut']) || !isset($_SESSION['prenom'])) {

    header("Location: 401.php");


} else {

session_destroy();
header('Location: index.php');
exit;

}

?>
