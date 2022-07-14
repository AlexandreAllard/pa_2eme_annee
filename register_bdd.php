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
if ( count($_POST) >=12
	&& !empty($_POST["lastName"])
	&& !empty($_POST["firstName"])
	&& !empty($_POST["email"])
	&& !empty($_POST["pwd"])
	&& !empty($_POST["pwdConfirm"])
	&& !empty($_POST["birthdate"])
	&& !empty($_POST["phone"])
	&& !empty($_POST["numWay"])
	&& !empty($_POST["typeWay"])
	&& !empty($_POST["nameWay"])
	&& !empty($_POST["postalCode"])
	&& !empty($_POST["city"])
	//&& !empty($_POST["moreInfos"])
	// && !empty($_POST["captcha"])
) {



	/* Nettoyage */

	$lastName = mb_strtoupper(trim($_POST["lastName"]));
	$firstName = ucwords(mb_strtolower(trim($_POST["firstName"])));
	$email = mb_strtolower(trim($_POST["email"]));
	$pwd = $_POST["pwd"];
	$pwdConfirm = $_POST["pwdConfirm"];
	$birthdate = $_POST["birthdate"];
	$phone = $_POST["phone"];
	$numWay = $_POST["numWay"];
	$typeWay = $_POST["typeWay"];
	$nameWay = $_POST["nameWay"];
	$postalCode = $_POST["postalCode"];
	$city = $_POST["city"];
	if(empty($_POST["moreInfos"])){
		$moreInfos = "";
	}else{
		$moreInfos = $_POST["moreInfos"];
	}
	$cle = rand(1000000,9000000);


	$listOfErrors = [];

	/*
	if( $_POST["captcha"] != $_SESSION["captcha"] ) {
		$listOfErrors[] = "Erreur sur le captcha";
	}
	*/

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

		$queryPrepared = $connection->prepare("SELECT adresse_mail FROM caf_utilisateur WHERE adresse_mail=:adresse_mail");

		$queryPrepared->execute(["adresse_mail"=>$email]);

		if( $queryPrepared->rowCount() != 0 ){
			$listOfErrors[] =  "Votre email existe déjà pour un autre compte";
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


	//mot de passe : Min 1 majuscule/chiffre/minuscule, min de 8
	// regex : #[a-z]# , #[A-Z]#, #[0-9]#
	if( strlen($pwd)<8
		|| !preg_match("#[a-z]#", $pwd)
		|| !preg_match("#[A-Z]#", $pwd)
		|| !preg_match("#[0-9]#", $pwd)
	 ) {

		$listOfErrors[] =  "Votre mot de passe doit faire au minimum 8 caractères dont 1 minuscule, 1 majuscule et 1 chiffre";

	}



	//mot de passe de confirmation = mot de passe
	if( $pwd != $pwdConfirm){
		$listOfErrors[] =  "Les deux mots de passe ne correspondent pas !";
	}


	//Si $listOfErrors est vide le formulaire est OK
	if( empty($listOfErrors) ){
		//Enregistrer en bdd l'utilisateur
		//Insertion de 'utilisateur en bdd'

		$queryPrepared =  $connection->prepare("INSERT INTO caf_utilisateur (nom, prenom, adresse_mail, mot_de_passe, numero_de_telephone, date_de_naissance, numero_voie, type_voie, nom_voie, code_postal, ville, indication_supplementaire, cle) VALUES ( :nom, :prenom, :adresse_mail, :mot_de_passe, :numero_de_telephone, :date_de_naissance, :numero_voie, :type_voie, :nom_voie, :code_postal, :ville, :indication_supplementaire, :cle);");


		$pwd = password_hash($pwd, PASSWORD_DEFAULT);


		$queryPrepared->execute( ["nom"=>$lastName,"prenom"=>$firstName,"adresse_mail"=>$email,"mot_de_passe"=>$pwd,"numero_de_telephone"=>$phone,"date_de_naissance"=>$birthdate,"numero_voie"=>$numWay,"type_voie"=>$typeWay,"nom_voie"=>$nameWay,"code_postal"=>$postalCode,"ville"=>$city,"indication_supplementaire"=>$moreInfos,"cle"=>$cle] );


		$results = $queryPrepared->fetch();

		session_start();
	   $_SESSION["id"] = $results["id_client"];
		 $_SESSION["prenom"] = $results["prenom"];

		header("Location: index.php");


/*
		//PHPmailer --> confirmation de mail
		 $recupUser = $connection->prepare('SELECT * FROM caf_utilisateur WHERE adresse_mail = ?');
		 $recupUser->execute(array($email));

			if ($recupUser->rowCount()>0) {
						 	$userInfo = $recupUser->fetch();
						 	$_SESSION['id'] = $userInfo['id'];
							//envoie les mails
							function smtpmailer($to, $from, $from_name, $subject, $body)
							{
								$mail = new PHPMailer();
								$mail->IsSMTP();
								$mail->SMTPAuth = true;

								$mail->SMTPSecure = 'ssl';
								$mail->Host = 'smtp.gmail.com';
								$mail->Port = 465;
								$mail->Username = 'trotterfly.contact@gmail.com';
								 // /!\ MONTRER CE MDP A PERSONNE !!!!!!!
																																																																																																																							$mail->Password = 'Teamrocket95.';

								$mail->IsHTML(true);
								$mail->From="trotterfly.contact@gmail.com";
								$mail->FromName=$from_name;
								$mail->Sender=$from;
								$mail->AddReplyTo($from, $from_name);
								$mail->Subject = $subject;
								$mail->Body = $body;
								$mail->AddAddress($to);

								if(!$mail->Send())
								{
									$error ="Erreur lors de l'envoi";
									return $error;
								}
								else
								{
									$error = "Votre email a été envoyé";
									return $error;
								}

							}


							$recupId = $_SESSION['id'];


							$to   = $email;
							$from = 'trotterfly.contact@gmail.com';
							$name = 'Trotterfly';
							$subj = 'Confirmation de compte Trotterfly';
							$msg = 'Bien le bonjour ! <br><br>
							Veuillez <a href="verif_mail.php?id=' . $recupId . '&cle=' . $cle . '">cliquer ici</a> pour valider votre compte.' .
							'<br><br>Bien cordialement,<br>

							La fameuse équipe Trotterfly';

							$error=smtpmailer($to,$from, $name ,$subj, $msg);
		}


	header("Location: pageDattente.php");



*/


	}
	//Sinon il y a eu des erreurs
	else{
		//Afficher les erreurs sur la page form.php
		$_SESSION["listOfErrors"] = $listOfErrors;
		header("Location: register.php");
	}



} else {
	die("Tous les champs ne sont pas valides");
}
