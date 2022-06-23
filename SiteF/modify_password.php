
<?php

session_start();

require "functions.php";




if( count($_POST)==4
	&& !empty($_POST["pwd_old"])
	&& !empty($_POST["new_pwd"])
	&& !empty($_POST["new_pwd_conf"])

) {
	$userId = $_SESSION['id'];
	$pwd_old = $_POST["pwd_old"];
	$new_pwd = $_POST["new_pwd"];
	$new_pwd_conf = $_POST["new_pwd_conf"];
	$listOfErrors = [];
	$listSuccess = [];



	// Vérification ancien mot de passe
	if(empty($listOfErrors)){

		$connection = connectDB();

		$queryPrepared = $connection->prepare("SELECT * FROM caf_utilisateur WHERE id_client=:id_client");
		$queryPrepared->execute(["id_client"=>$userId]);

		$result = $queryPrepared->fetch();

		if(empty($result)){

			$listOfErrors[] = "Il y a un problème... Veuillez réessayer ultérieurement";
			header("Location: account.php#scroll_modify_password");


			} else if(password_verify($pwd_old, $result["mot_de_passe"]) ){

					if( $new_pwd == $new_pwd_conf ) {


						if( strlen($new_pwd)<8
							|| !preg_match("#[a-z]#", $new_pwd)
							|| !preg_match("#[A-Z]#", $new_pwd)
							|| !preg_match("#[0-9]#", $new_pwd)
						 ) {

							$listOfErrors[] =  "Votre nouveau mot de passe doit faire au minimum 8 caractères dont 1 minuscule, 1 majuscule et 1 chiffre";

						} else {

						$pwd_hash = password_hash($new_pwd, PASSWORD_DEFAULT);


						$req = $connection->prepare("UPDATE caf_utilisateur SET mot_de_passe = '{$pwd_hash}' WHERE id_client = '{$userId}' ");

			            $req->execute();

			            $listSuccess[] = "Votre mot de passe a bien été modifié";

			        	}


	            	} else{
						$listOfErrors[] = "Les nouveaux mots de passe ne correspondent pas";
						echo $listOfErrors;
					}

				} else{
						$listOfErrors[] = "L'ancien mot de passe n'est pas correct";
						echo $listOfErrors;
				}

			if(!empty($listOfErrors)) {

				$_SESSION["listOfErrorsPassword"] = $listOfErrors;
				header("Location: account.php#scroll_modify_password");

			} else if( !empty($listSuccess)) {

				$_SESSION["listSuccessPassword"] = $listSuccess;
			header("Location: account.php#scroll_modify_password");
			}

	}

} else {echo "Il y a un problème";
	}
