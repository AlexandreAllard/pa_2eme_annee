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

		$queryPrepared = $connection->prepare("SELECT * FROM caf_utilisateur WHERE adresse_mail=:adresse_mail");
		$queryPrepared->execute(["adresse_mail"=>$email]);

		$results = $queryPrepared->fetch();

		if(empty($results)){

			$listOfErrors[] = "Identifiants incorrects";

		}
		if(!empty($results && $results['etat'] == 0)){

			$listOfErrors[] = "Merci d'aller confirmer votre compte à l'aide du mail reçu sur votre adresse.";

		}
	}

	// Vérification mot de passe
	if(empty($listOfErrors)){
			if(password_verify($pwd, $results["mot_de_passe"]) ){


			session_start();
	    $_SESSION["id"] = $results["id_client"];
	    $_SESSION["prenom"] = $results["prenom"];
    	$_SESSION["info"]=$results;
			$_SESSION["auth"]=true;
			$_SESSION["statut"]=$results["statut"];

				if($results["statut"] == "admin"){
					//header("Location: admin.php");
					header("Location: index.php");
				}else{header("Location: index.php");}

				}else{
					$listOfErrors[] = "Identifiants incorrects";
				}
		}

	if(!empty($listOfErrors)){
		$_SESSION["listOfErrors"] = $listOfErrors;
		header("Location: login.php");
	}

}else {
	die("Les champs ne sont pas valides");
}
