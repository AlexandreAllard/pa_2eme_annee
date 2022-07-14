<?php
require "conf.php";

function connectDB()
{
	try{
		$connection = new PDO(DBDRIVER.":host=".DBHOST.";dbname=".DBNAME , DBUSER, DBPWD);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}catch(Exception $e){
		die("Erreur SQL : ". $e->getMessage());
	}
	return $connection;
}

function navbar(){
?>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
   <div class="container px-5">
       <a class="navbar-brand" href="index.php">TROTTERFLY</a>
       <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
			 <div class="collapse navbar-collapse" id="navbarSupportedContent">
				 <div class="navbar-nav ms-auto mb-2 mb-lg-0">

					 <!-- Image météo actuelle -->
					 <?php
					 date_default_timezone_set('Europe/Paris');
					 $date = date('d-m-y H:i:s');
					 $hourParis = date('H');

					 $connection = connectDB();
					 $hourBDD = $connection->prepare("SELECT Max(heure) as currentHour from caf_meteo WHERE (((heure)<=:heure))");
					 $hourBDD->execute(["heure"=>$hourParis]);
					 $currentHour = $hourBDD->fetch();
					 $hourOfReference = $currentHour['currentHour'];

					 $hourBDDMeteo = $connection->prepare("SELECT * from caf_meteo WHERE heure=:heure");
					 $hourBDDMeteo->execute(["heure"=>$hourOfReference]);
					 $currentHour = $hourBDDMeteo->fetch();
					 ?>
					 <a class="nav-item" href="meteo.php"><img class="nav-item" src="meteo/<?php echo $currentHour['description']; ?>.png" alt="Image" height="42" width="42"/><button class="buttonGoMeteo nav-item">Votre météo à Lyon</button></a>


				 </div>
           <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
               <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
               <li class="nav-item"><a class="nav-link" href="pricing.php">Forfaits - Tarifs</a></li>
               <li class="nav-item"><a class="nav-link" href="shop.php">Boutique</a></li>
               <li class="nav-item"><a class="nav-link" href="about.php">A propos</a></li>

							 <?php
	                 if (isset($_SESSION["id"]) AND isset($_SESSION["prenom"]) AND isset($_SESSION["statut"])) {
	                 if(($_SESSION["statut"]) == "admin") { ?>
	                     <li class="nav-item"><a class="nav-link" href="admin.php"><b>Centre d'administration</b></a></li>
	             <?php 	}
						 			 } ?>

							 <?php
										if (!isset($_SESSION["id"]) AND !isset($_SESSION["prenom"]))
											 {
												 ?>
													<li class="nav-item dropdown">
															<a class="nav-link dropdown-toggle" id="navbarDropdownPortfolio" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Connexion</a>
															<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownPortfolio">
																	<li><a class="dropdown-item" href="login.php">Espace client</a></li>
																	<li><a class="dropdown-item" href="login_collector.php">Espace collecteur</a></li>
															</ul>
													</li>
													<li class="nav-item"><a class="nav-link" href="register.php">S'inscrire</a></li>

												<?php } else if(isset($_SESSION["id"]) AND isset($_SESSION["prenom"]))
													{
														$userId = $_SESSION["id"];
														$connection = connectDB();
														$reqUserName = $connection->prepare("SELECT prenom FROM caf_utilisateur WHERE id_client=:id_client");
														$reqUserName->execute(['id_client'=>$userId]);
														$userName = $reqUserName->fetch();
														$userName = $userName["prenom"];
														?>
													<li class="nav-item dropdown">
															<a class="nav-link dropdown-toggle" id="navbarDropdownPortfolio" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><b><?php echo 'Bonjour ' . $userName;?></b></a>
															<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownPortfolio">
																	<li><a class="dropdown-item" href="account.php">Mon compte</a></li>
																	<li><a class="dropdown-item" href="reservations.php">Mes réservations</a></li>
																	<li><a class="dropdown-item" href="orders.php">Mes achats</a></li>
																	<li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
															</ul>
													</li>
											<?php }	 ?>

           </ul>
       </div>
   </div>
  </nav>
<?php }


function navbarCollector(){
?>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
   <div class="container px-5">
       <a class="navbar-brand" href="index.php">TROTTERFLY</a>
       <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
			 <div class="collapse navbar-collapse" id="navbarSupportedContent">
				 <div class="navbar-nav ms-auto mb-2 mb-lg-0">

					 <!-- Image météo actuelle -->
					 <?php
					 date_default_timezone_set('Europe/Paris');
					 $date = date('d-m-y H:i:s');
					 $hourParis = date('H');

					 $connection = connectDB();
					 $hourBDD = $connection->prepare("SELECT Max(heure) as currentHour from caf_meteo WHERE (((heure)<=:heure))");
					 $hourBDD->execute(["heure"=>$hourParis]);
					 $currentHour = $hourBDD->fetch();
					 $hourOfReference = $currentHour['currentHour'];

					 $hourBDDMeteo = $connection->prepare("SELECT * from caf_meteo WHERE heure=:heure");
					 $hourBDDMeteo->execute(["heure"=>$hourOfReference]);
					 $currentHour = $hourBDDMeteo->fetch();
					 ?>


				 </div>
           <ul class="navbar-nav ms-auto mb-2 mb-lg-0">


							 <?php
										if (!isset($_SESSION["id"]) AND !isset($_SESSION["prenom"]))
											 {
												 ?>
													<li class="nav-item dropdown">
															<a class="nav-link dropdown-toggle" id="navbarDropdownPortfolio" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Connexion</a>
															<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownPortfolio">
																	<li><a class="dropdown-item" href="login.php">Espace client</a></li>
																	<li><a class="dropdown-item" href="login_collector.php">Espace collecteur</a></li>
															</ul>
													</li>

												<?php } else if(isset($_SESSION["id"]) AND isset($_SESSION["prenom"]))
													{
														$userId = $_SESSION["id"];
														$connection = connectDB();
														$reqUserName = $connection->prepare("SELECT prenom FROM caf_collecteur WHERE id_collecteur=:id_collecteur");
														$reqUserName->execute(['id_collecteur'=>$userId]);
														$userName = $reqUserName->fetch();
														$userName = $userName["prenom"];
														?>
													<li class="nav-item dropdown">
															<a class="nav-link dropdown-toggle" id="navbarDropdownPortfolio" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><b><?php echo 'Bonjour ' . $userName;?></b></a>
															<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownPortfolio">
																	<li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
															</ul>
													</li>
											<?php }	 ?>

           </ul>
       </div>
   </div>
  </nav>
<?php } ?>


<?php
function translateMeteo($stateMeteo){
	if($stateMeteo == "Atmosphere"){return $currentTimeState= "Dangereux";}
	if($stateMeteo == "Clear"){return $currentTimeState= "Ciel dégagé";}
	if($stateMeteo == "Clouds"){return $currentTimeState= "Nuageux";}
	if($stateMeteo == "Drizzle"){return $currentTimeState= "Bruineux";}
	if($stateMeteo == "Rain"){return $currentTimeState= "Pluvieux";}
	if($stateMeteo == "Snow"){return $currentTimeState= "Neigeux";}
	if($stateMeteo == "Thunderstorm"){return $currentTimeState= "Orageux";}
}
?>

<?php
// Return le nombre de km (url = url de l'API maps)
function getDistance($url){
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    //print_r($data);
    $distance=$data['rows'][0]['elements'][0]['distance']['text'];
    //echo $distance;
    $km = substr($distance,0 ,-3);
    return $km;
}


function calculFidelite($itemPrice){
		$bonus=0;
		//itemPrice en €
		// Calcul point fidélité
		$pt_fidelite=$itemPrice;

		// +100 == +1 $bonus
		if ($pt_fidelite>=100) {
			$bonus=$pt_fidelite/100;
		}
		// Resultat sans arrondi avec bonus
		$pt_fidelite=$pt_fidelite*0.3+$bonus;

		// Arronndi à l'unité
		if ($pt_fidelite-(int)$pt_fidelite>=0.5) {
			$pt_fidelite=(int)$pt_fidelite+1;
		}else {
			$pt_fidelite=(int)$pt_fidelite;
		}
		return $pt_fidelite;
}


function finalPrice($itemPrice, $fidelite_use){
		$itemPrice = $itemPrice - $fidelite_use*0.2;
		return $itemPrice;
}
 ?>
