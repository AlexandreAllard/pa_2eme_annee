<?php
session_start();
require "functions.php";

if(!isset($_SESSION['statut']) || $_SESSION['statut'] == 'client' || !isset($_SESSION['prenom'])) {

    header("Location: 403.php");


} else {
// FAILLE XSS
// Vérifier qu'il y a 11 valeurs et que les champs obligatoires ne soient pas vides
if ( count($_POST) > 1
	&& !empty($_POST["nom_categorie"])
	&& !empty($_POST["description_categorie"])
) {


	/* Nettoyage */
	$categoryName = ucwords(mb_strtolower(trim($_POST["nom_categorie"])));
	$categoryDescription = $_POST["description_categorie"];

	$listOfErrorsCategory = [];
	$listSuccessCategory = [];

	if( strlen($categoryName)<2 || strlen($categoryName)>100 ) {
		$listOfErrorsCategory[] =  "Le nom de la catégorie doit faire entre 2 et 100 caractères";
	}
	if( strlen($categoryDescription)< 1 || strlen($categoryDescription)> 250 ) {
		$listOfErrorsCategory[] =  "La description de la catégorie ne doit pas faire plus de 500 caractères";
	}

	//Si $listOfErrors est vide le formulaire est OK
	if( empty($listOfErrorsCategory) ){

		$connection = connectDB();

		$queryPrepared =  $connection->prepare("INSERT INTO caf_categorie_produit (nom_categorie, description_categorie) VALUES ( :nom_categorie, :description_categorie);");

		$queryPrepared->execute( ["nom_categorie"=>$categoryName,"description_categorie"=>$categoryDescription] );

		$results = $queryPrepared->fetch();

    $listSuccessCategory[] = "La catégorie a bien été ajoutée !";

	}
	//Sinon il y a eu des erreurs
	else{
		//Afficher les erreurs sur la page form.php
		$_SESSION["listOfErrorsCategory"] = $listOfErrorsCategory;
		header("Location: admin.php#scroll_add_category");

	}

	if( empty($listSuccessProducts)) {

		$_SESSION["listSuccessCategory"] = $listSuccessCategory;
		header("Location: admin.php#scroll_add_category");
	}

} else {
	die("Tous les champs ne sont pas valides");
}

}
