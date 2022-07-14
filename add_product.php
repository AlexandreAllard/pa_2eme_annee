<?php
session_start();
require "functions.php";

if(!isset($_SESSION['statut']) || $_SESSION['statut'] == 'client' || !isset($_SESSION['prenom'])) {

    header("Location: 403.php");


} else {
// FAILLE XSS
// Vérifier qu'il y a 11 valeurs et que les champs obligatoires ne soient pas vides
if ( count($_POST) > 3
	&& !empty($_POST["categorie_produit"])
	&& !empty($_POST["nom_produit"])
	&& !empty($_POST["prix_produit"])
	&& !empty($_POST["description_produit"])
) {


	/* Nettoyage */
	$productName = ucwords(mb_strtolower(trim($_POST["nom_produit"])));
	$productCategory = $_POST["categorie_produit"];
	$productPrice = $_POST["prix_produit"];
	$productDescription = $_POST["description_produit"];
	$productPhoto = "Test";
	$listOfErrorsProducts = [];
	$listSuccessProducts = [];

	if( strlen($productName)<2 || strlen($productName)>100 ) {
		$listOfErrorsProducts[] =  "Le nom du produit doit faire entre 2 et 100 caractères";
	}
	if( strlen($productPrice)<1 || strlen($productPrice)>7 ) {
		$listOfErrorsProducts[] = "Le prix du produit ne doit pas faire plus de 7 caratères";
	}
	if( strlen($productDescription)< 1 || strlen($productDescription)> 500 ) {
		$listOfErrorsProducts[] =  "La description ne doit pas faire plus de 500 caractères";
	}

//Traitement photo
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["photo_produit"]["name"]);
	$productPhoto = $target_file;
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	// Check if image file is a actual image or fake image
	if(isset($_POST["submit_add_product"])) {
	  $check = getimagesize($_FILES["photo_produit"]["tmp_name"]);
	  if($check !== false) {
	    //echo "File is an image - " . $check["mime"] . ".";
	    $uploadOk = 1;
	  } else {
	    $listOfErrorsProducts[] =  "File is not an image.";
	    $uploadOk = 0;
	  }
	}

	// Check if file already exists
	if (file_exists($target_file)) {
	  $listOfErrorsProducts[] =  "Sorry, file already exists.";
	  $uploadOk = 0;
	}

	// Check file size
	if ($_FILES["photo_produit"]["size"] > 500000) {
	  $listOfErrorsProducts[] =  "Sorry, your file is too large.";
	  $uploadOk = 0;
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	  $listOfErrorsProducts[] =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	  $uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	  $listOfErrorsProducts[] =  "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	  if (move_uploaded_file($_FILES["photo_produit"]["tmp_name"], $target_file)) {
	    $listSuccessProducts[] =  "The file ". htmlspecialchars( basename( $_FILES["photo_produit"]["name"])). " has been uploaded.";
	  } else {
	    $listOfErrorsProducts[] =  "Sorry, there was an error uploading your file.";
	  }
	}
// fin traitement image


	//Si $listOfErrors est vide le formulaire est OK
	if( empty($listOfErrorsProducts) ){

		$connection = connectDB();

		//Gestion de la Catégorie
		$reqSearchCategory= $connection->prepare("SELECT id_categorie FROM caf_categorie_produit WHERE nom_categorie='{$productCategory}'");
		$reqSearchCategory->execute();
		$idCategory = $reqSearchCategory->fetch();
		$idCategoryEnd = $idCategory["id_categorie"];

		$queryPrepared =  $connection->prepare("INSERT INTO caf_produit (nom_produit, prix_produit, description_produit, photo_produit, id_categorie) VALUES ( :nom_produit, :prix_produit, :description_produit, :photo_produit, :id_categorie);");

		$queryPrepared->execute( ["nom_produit"=>$productName,"prix_produit"=>$productPrice,"description_produit"=>$productDescription,"photo_produit"=>$productPhoto,"id_categorie"=>$idCategoryEnd] );

		$results = $queryPrepared->fetch();

    $listSuccessProducts[] = "Le produit a bien été ajouté !";

	}
	//Sinon il y a eu des erreurs
	else{
		//Afficher les erreurs sur la page form.php
		$_SESSION["listOfErrorsProducts"] = $listOfErrorsProducts;
		header("Location: admin.php#scroll_add_product");

	}

	if( !empty($listSuccessProducts)) {

		$_SESSION["listSuccessProducts"] = $listSuccessProducts;
		header("Location: admin.php#scroll_add_product");
	}

} else {
	die("Tous les champs ne sont pas valides");
}
}
