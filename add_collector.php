<?php
session_start();
require "functions.php";

if(!isset($_SESSION['statut']) || $_SESSION['statut'] == 'client' || !isset($_SESSION['prenom'])) {

    header("Location: 403.php");


} else {
// FAILLE XSS
// Vérifier qu'il y a 11 valeurs et que les champs obligatoires ne soient pas vides
if ( count($_POST) > 4
	&& !empty($_POST["nom_collecteur"])
	&& !empty($_POST["prenom_collecteur"])
	&& !empty($_POST["email_collecteur"])
	&& !empty($_POST["mdp_collecteur"])
	&& !empty($_POST["telephone_collecteur"])
) {


	/* Nettoyage */
	$lastName = mb_strtoupper(trim($_POST["nom_collecteur"]));
	$firstName = ucwords(mb_strtolower(trim($_POST["prenom_collecteur"])));
	$email = mb_strtolower(trim($_POST["email_collecteur"]));
	$pwd = $_POST["mdp_collecteur"];
	$phone = $_POST["telephone_collecteur"];

	$listOfErrorsCollector = [];
	$listSuccessCollector = [];

		//Nom : min:2 max:100
	if( strlen($lastName)<2 || strlen($lastName)>100 ) {
		$listOfErrorsCollector[] =  "Le nom doit faire entre 2 et 100 caractères";
	}

	//Prénom : min:2 max:50
	if( strlen($firstName)<2 || strlen($firstName)>50 ) {
		$listOfErrorsCollector[] = "Le prénom doit faire entre 2 et 50 caractères";
	}


	//Email : vérification du format
	if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
		$listOfErrorsCollector[] =  "L'email n'est pas correct";

	}

	//mot de passe : Min 1 majuscule/chiffre/minuscule, min de 8
	// regex : #[a-z]# , #[A-Z]#, #[0-9]#
	if( strlen($pwd)<8
		|| !preg_match("#[a-z]#", $pwd)
		|| !preg_match("#[A-Z]#", $pwd)
		|| !preg_match("#[0-9]#", $pwd)
	 ) {

		$listOfErrorsCollector[] =  "Le mot de passe doit faire au minimum 8 caractères dont 1 minuscule, 1 majuscule et 1 chiffre";

	}

	//Si $listOfErrors est vide le formulaire est OK
	if( empty($listOfErrorsCollector) ){

		$connection = connectDB();

		$queryPrepared =  $connection->prepare("INSERT INTO caf_collecteur (nom, prenom, adresse_mail, mot_de_passe, numero_de_telephone) VALUES ( :nom, :prenom, :adresse_mail, :mot_de_passe, :numero_de_telephone);");

		$pwd = password_hash($pwd, PASSWORD_DEFAULT);

		$queryPrepared->execute( ["nom"=>$lastName,"prenom"=>$firstName,"adresse_mail"=>$email,"mot_de_passe"=>$pwd,"numero_de_telephone"=>$phone] );

		$results = $queryPrepared->fetch();

    $listSuccessCollector[] = "Le collecteur a bien été ajouté !";

	}
	//Sinon il y a eu des erreurs
	else{
		//Afficher les erreurs sur la page form.php
		$_SESSION["listOfErrorsCollector"] = $listOfErrorsCollector;
		header("Location: admin.php#scroll_add_collector");

	}

	if( !empty($listSuccessCollector)) {

		$_SESSION["listSuccessCollector"] = $listSuccessCollector;
		header("Location: admin.php#scroll_add_collector");
	}

} else {
	die("Tous les champs ne sont pas valides");
}
}
