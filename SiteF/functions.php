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
											 { ?>
													<li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
													<li class="nav-item"><a class="nav-link" href="register.php">S'inscrire</a></li>

												<?php } else if(isset($_SESSION["id"]) AND isset($_SESSION["prenom"]))
													{ ?>
													<li class="nav-item dropdown">
															<a class="nav-link dropdown-toggle" id="navbarDropdownPortfolio" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><b><?php echo 'Bonjour ' . $_SESSION["prenom"];?></b></a>
															<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownPortfolio">
																	<li><a class="dropdown-item" href="account.php">Mon compte</a></li>
																	<li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
															</ul>
													</li>
											<?php }	 ?>

           </ul>
       </div>
   </div>
  </nav>
<?php } ?>
