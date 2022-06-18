<?php
session_start();
//require "PHPMailer/PHPMailerAutoload.php";
require "functions.php";


/*
Vérifier les champs - les données
Si OK -> insertion en BDD et redirection que la page de connexion
Si NOK -> on redirige vers la page d'inscription avec les msg d'erreurs
*/

// FAILLE XSS
// Vérifier qu'il y a 11 valeurs et que les champs obligatoires ne soient pas vides
if ( count($_POST) == 12
	&& !empty($_POST["lastName"])
	&& !empty($_POST["firstName"])
	&& !empty($_POST["email"])
	&& !empty($_POST["birthdate"])
	&& !empty($_POST["phone"])
	&& !empty($_POST["numWay"])
	&& !empty($_POST["typeWay"])
	&& !empty($_POST["nameWay"])
	&& !empty($_POST["postalCode"])
	&& !empty($_POST["city"])
	&& !empty($_POST["moreInfos"])
) {



	/* Nettoyage */

	$lastName = mb_strtoupper(trim($_POST["lastName"]));
	$firstName = ucwords(mb_strtolower(trim($_POST["firstName"])));
	$email = mb_strtolower(trim($_POST["email"]));
	$birthdate = $_POST["birthdate"];
	$phone = $_POST["phone"];
	$numWay = $_POST["numWay"];
	$typeWay = $_POST["typeWay"];
	$nameWay = $_POST["nameWay"];
	$postalCode = $_POST["postalCode"];
	$city = $_POST["city"];
	$moreInfos = $_POST["moreInfos"];

	$userId = $_SESSION['id'];
	$listOfErrors = [];
	$listSuccess = [];

	//Nom : min:2 max:100
	if( strlen($lastName)<2 || strlen($lastName)>100 ) {
		$listOfErrors[] =  "Votre nom doit faire entre 2 et 100 caractères";
	}


	//Prénom : min:2 max:50
	if( strlen($firstName)<2 || strlen($firstName)>50 ) {
		$listOfErrors[] = "Votre prénom doit faire entre 2 et 50 caractères";
	}


	//Email : vérification du format
	if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
		$listOfErrors[] =  "Votre email n'est pas correct";

	}else{

		//Vérifier l'unicté de l'adresse email
		//$listOfErrors[] =  "Votre email existe déjà pour un autre compte";
		$connection = connectDB();

		$queryPrepared1 = $connection->prepare("SELECT adresse_mail FROM caf_utilisateur WHERE id_client=:id_client");
		$queryPrepared1->execute(["id_client"=>$userId]);
		$result = $queryPrepared1->fetch();
		if( $result['adresse_mail'] != $email){

			$queryPrepared2 = $connection->prepare("SELECT adresse_mail FROM caf_utilisateur WHERE adresse_mail=:adresse_mail");
			$queryPrepared2->execute(["adresse_mail"=>$email]);

			if( $queryPrepared2->rowCount() != 0 ){
				$listOfErrors[] =  "Votre email existe déjà pour un autre compte";
			}
		}

	}


	//Numéro de voie : min:1 max:11
	if( strlen($numWay)<1 || strlen($numWay)>11 ) {
		$listOfErrors[] =  "Le numéro de voie doit faire entre entre 1 et 11 caractères";
	}
	//Type de voie : min:1 max:255
	if( strlen($typeWay)<1 || strlen($typeWay)>255 ) {
		$listOfErrors[] =  "Le type de voie doit faire entre entre 1 et 255 caractères";
	}
	//Nom de voie : min:1 max:255
	if( strlen($nameWay)<1 || strlen($nameWay)>255 ) {
		$listOfErrors[] =  "Le nom de voie doit faire entre entre 1 et 255 caractères";
	}


	//Code postal : taille = 5
	if( strlen($postalCode) != 5 ) {
		$listOfErrors[] =  "Le code postal renseigné est invalide";
	}


	//Adresse : min:2 max:255
	if( strlen($city)<2 || strlen($city)>255 ) {
		$listOfErrors[] =  "La ville renseignée doit faire entre entre 2 et 255 caractères";
	}


	//Si $listOfErrors est vide le formulaire est OK
	if( empty($listOfErrors) ){
		//Enregistrer en bdd l'utilisateur
		//Insertion de 'utilisateur en bdd'

		$connection = connectDB();
		$queryPrepared = $connection->prepare("SELECT * FROM caf_utilisateur WHERE id_client=:id_client");
		$queryPrepared->execute(["id_client"=>$userId]);
		$result = $queryPrepared->fetch();

		if(empty($result)){

			$listOfErrors[] = "Il y a un problème... Veuillez réessayer ultérieurement";
			header("Location: account.php#scroll_modify_infos");


		} else{

		$req =  $connection->prepare("UPDATE caf_utilisateur
																						SET nom='{$lastName}',
																						prenom='{$firstName}',
																						adresse_mail='{$email}',
																						numero_de_telephone='{$phone}',
																						date_de_naissance='{$birthdate}',
																						numero_voie='{$numWay}',
																						type_voie='{$typeWay}',
																						nom_voie='{$nameWay}',
																						code_postal='{$postalCode}',
																						ville = '{$city}',
																						indication_supplementaire='{$moreInfos}'
																						 WHERE id_client='{$userId}' ");

		$req->execute();

		$listSuccess[] = "Vos informations ont bien été modifiées !";
		}
}

	//Sinon il y a eu des erreurs
	if(!empty($listOfErrors)) {
		//Afficher les erreurs sur la page form.php
		$_SESSION["listOfErrors"] = $listOfErrors;
		header("Location: account.php#scroll_modify_infos");

	} else if( !empty($listSuccess)) {

		$_SESSION["listSuccess"] = $listSuccess;
		header("Location: account.php#scroll_modify_infos");
	}

} else {
	die("Tous les champs ne sont pas valides");
}
