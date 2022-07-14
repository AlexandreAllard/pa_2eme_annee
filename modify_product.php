<?php
session_start();
require "functions.php";


// FAILLE XSS
// Vérifier qu'il y a 11 valeurs et que les champs obligatoires ne soient pas vides
if ( count($_POST) > 2
	&& !empty($_POST["nom_produit"])
	&& !empty($_POST["prix_produit"])
	&& !empty($_POST["description_produit"])
) {


	/* Nettoyage */
	$productId = $_POST["id_produit"];
	$productName = ucwords(mb_strtolower(trim($_POST["nom_produit"])));
	$productPrice = $_POST["prix_produit"];
	$productDescription = $_POST["description_produit"];
	$productPhoto = "Test";

	$listOfErrorsModifyProducts = [];
	$listSuccessModifyProducts = [];

	if( strlen($productName)<2 || strlen($productName)>100 ) {
		$listOfErrorsModifyProducts[] =  "Le nom du produit doit faire entre 2 et 100 caractères";
	}
	if( strlen($productPrice)<1 || strlen($productPrice)>7 ) {
		$listOfErrorsModifyProducts[] = "Le prix du produit ne doit pas faire plus de 7 caratères";
	}
	if( strlen($productDescription)< 1 || strlen($productDescription)> 500 ) {
		$listOfErrorsModifyProducts[] =  "La description ne doit pas faire plus de 500 caractères";
	}

//Traitement photo
if(isset($_POST["photo_produit"])){
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["photo_produit"]["name"]);
	$productPhoto = $target_file;
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	// Check if image file is a actual image or fake image
	if(isset($_POST["submit_modify_product"])) {
		$check = getimagesize($_FILES["photo_produit"]["tmp_name"]);
		if($check !== false) {
			//echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			$listOfErrorsModifyProducts[] =  "File is not an image.";
			$uploadOk = 0;
		}
	}

	// Check if file already exists
	if (file_exists($target_file)) {
		$listOfErrorsModifyProducts[] =  "Sorry, file already exists.";
		$uploadOk = 0;
	}

	// Check file size
	if ($_FILES["photo_produit"]["size"] > 500000) {
		$listOfErrorsModifyProducts[] =  "Sorry, your file is too large.";
		$uploadOk = 0;
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		$listOfErrorsModifyProducts[] =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$listOfErrorsModifyProducts[] =  "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["photo_produit"]["tmp_name"], $target_file)) {
			$listSuccessModifyProducts[] =  "The file ". htmlspecialchars( basename( $_FILES["photo_produit"]["name"])). " has been uploaded.";
		} else {
			$listOfErrorsModifyProducts[] =  "Sorry, there was an error uploading your file.";
		}
	}
}

// fin traitement image


	//Si $listOfErrors est vide le formulaire est OK
	if( empty($listOfErrorsModifyProducts) ){

		$connection = connectDB();

		if(!isset($_POST["photo_produit"])){
			$queryPrepared =  $connection->prepare("UPDATE caf_produit SET nom_produit=:nom_produit, prix_produit=:prix_produit, description_produit=:description_produit WHERE id_produit=:id_produit ");
			$queryPrepared->execute(["nom_produit"=>$productName, "prix_produit"=>$productPrice, "description_produit"=>$productDescription, "id_produit"=>$productId]);

		}else{
			$queryPrepared =  $connection->prepare("UPDATE caf_produit SET nom_produit='{$productName}', prix_produit='{$productPrice}', description_produit='{$productDescription}', photo_produit='{$productPhoto}' WHERE id_produit=:id_produit ");
			$queryPrepared->execute(["nom_produit"=>$productName, "prix_produit"=>$productPrice, "description_produit"=>$productDescription, "photo_produit"=>$productPhoto, "id_produit"=>$productId]);

		}

		$results = $queryPrepared->fetch();

    $listSuccessModifyProducts[] = "Le produit a bien été modifié !";

	}
	//Sinon il y a eu des erreurs
	else{
		//Afficher les erreurs sur la page form.php
		$_SESSION["listOfErrorsModifyProducts"] = $listOfErrorsModifyProducts;
		header("Location: admin.php#scroll_modify_product");

	}

	if( !empty($listSuccessModifyProducts)) {

		$_SESSION["listSuccessModifyProducts"] = $listSuccessModifyProducts;
		header("Location: admin.php#scroll_modify_product");
	}

} else {
	die("Tous les champs ne sont pas valides");
}
