<?php

session_start();

require "functions.php";


if( count($_POST)==2
	&& !empty($_POST["email"])
	&& !empty($_POST["pwd"])

) {
	$email = $_POST["email"];
	$pwd = $_POST["pwd"];
	$listOfErrors = [];

	// Vérification email
	if(empty($listOfErrors)){

		$connection = connectDB();

		$queryPrepared = $connection->prepare("SELECT * FROM caf_collecteur WHERE adresse_mail=:adresse_mail");
		$queryPrepared->execute(["adresse_mail"=>$email]);

		$results = $queryPrepared->fetch();

		if(empty($results)){

			$listOfErrors[] = "Identifiants incorrects";

		}
	}

	// Vérification mot de passe
	if(empty($listOfErrors)){
			if(password_verify($pwd, $results["mot_de_passe"]) ){

				session_start();
		    $_SESSION["id"] = $results["id_collecteur"];
		    $_SESSION["prenom"] = $results["prenom"];
	    	$_SESSION["info"]=$results;
				$_SESSION["auth"]=true;
				$_SESSION["statut"]=$results["statut"];

				if($results["statut"] == "collecteur"){
					header("Location: app_collector.php");
				}else{
					$listOfErrors[] = "Identifiants incorrects";
				}
			}
	}

	if(!empty($listOfErrors)){
		$_SESSION["listOfErrors"] = $listOfErrors;
		header("Location: login_collector.php");
	}

}else {
	die("Les champs ne sont pas valides");
}
