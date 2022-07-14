<?php
session_start();
require "functions.php";


// FAILLE XSS
// Vérifier qu'il y a 11 valeurs et que les champs obligatoires ne soient pas vides
if ( count($_POST) > 2
	&& !empty($_POST["nom_category"])
	&& !empty($_POST["description_category"])
) {


	/* Nettoyage */
	$categoryId = $_POST["id_produit"];
	$categoryName = ucwords(mb_strtolower(trim($_POST["nom_category"])));
	$categoryDescription = $_POST["description_category"];

	$listOfErrorsModifyCategory = [];
	$listSuccessModifyCategory = [];

	if( strlen($categoryName)<2 || strlen($categoryName)>100 ) {
		$listOfErrorsModifyCategory[] =  "Le nom de la catégorie doit faire entre 2 et 100 caractères";
	}
	if( strlen($categoryDescription)< 1 || strlen($categoryDescription)> 250 ) {
		$listOfErrorsModifyCategory[] =  "La description de la catégorie ne doit pas faire plus de 250 caractères";
	}

	//Si $listOfErrors est vide le formulaire est OK
	if( empty($listOfErrorsModifyCategory) ){

		$connection = connectDB();

		$queryPrepared =  $connection->prepare("UPDATE caf_categorie_produit SET nom_categorie=:nom_categorie, description_categorie=:description_categorie WHERE id_categorie=:id_categorie ");
		$queryPrepared->execute(["nom_categorie"=>$categoryName, "description_categorie"=>$categoryDescription, "id_categorie"=>$categoryId]);


		$results = $queryPrepared->fetch();
    $listSuccessModifyCategory[] = "La catégorie a bien été modifiée !";

	}
	//Sinon il y a eu des erreurs
	else{
		//Afficher les erreurs sur la page form.php
		$_SESSION["listOfErrorsModifyCategory"] = $listOfErrorsModifyCategory;
		header("Location: admin.php#scroll_modify_category");

	}

	if( !empty($listSuccessModifyCategory)) {

		$_SESSION["listSuccessModifyCategory"] = $listSuccessModifyCategory;
		header("Location: admin.php#scroll_modify_category");
	}

} else {
	die("Tous les champs ne sont pas valides");
}
