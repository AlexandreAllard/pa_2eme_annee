<?php
session_start();
//Factorisation
//include "header.php";
//require "header.php";
require "functions.php";

if(!isset($_SESSION['statut']) || $_SESSION['statut'] == 'client' || !isset($_SESSION['prenom'])) {

    header("Location: 403.php");


} else {


?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Accueil Administrateur">
    <meta name="author" content="Trotterfly">

    <title>Espace d'administration Trotterfly</title>

	<link href="css/styles.css" rel="stylesheet" />
    <link href="css/stylesFnny.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/overlay.scss">
    <script type="text/javascript" src="js/overlay.js"></script>

<body>
	  <main class="flex-shrink-0">
      <!-- Navigation-->
      <?php if(isset($_SESSION['statut']) ){
        if($_SESSION['statut'] == "collecteur"){
      		navbarCollector();
        }else{
          navbar();
        }
    	}else{
    		navbar();
    	}  ?>
                <!-- Begin Page Content -->
                <div style="background-color: #FFF; display: grid; place-items: center; position: relative; overflow-x: hiden;" >
                    <!-- Page Heading -->
                    <div style="position: relative; left: -200px;">
                        <h1>Espace d'administration Trotterfly</h1>
                    </div>
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4" id="users">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h3 class="m-0 font-weight-bold text-primar chgcolor">Utilisateurs</h3>


                                    <form
                                    class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="POST" action="">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Nom ou email..."
                                            aria-label="Rechercher" aria-describedby="basic-addon2" name="recherche">
                                        <div class="input-group-append">

                                         <button class="btn btn-secondary" type="SUBMIT" name="submit_search_user" onclick="location.href='admin.php#users'">Rechercher un utilisateur
                                            </button>
                                        <?php

                                        if (isset($_POST['submit_search_user'])) {

                                             $connect = connectDB();

                                             // Récupère la recherche
                                             $recherche = isset($_POST['recherche']) ? $_POST['recherche'] : '';

                                             // la requête
                                             $req = $connect->query(

                                             "SELECT * FROM caf_utilisateur
                                              WHERE nom LIKE '%$recherche%'
                                              OR adresse_mail LIKE '%$recherche%'
                                              LIMIT 10");


                                            $count = $req->rowCount();
                                        }

                                        //On traite les résultats

                                          ?>

                                        </div>
                                    </div>
                                </form>
                                    </div>
                                    <!-- FIIN -->

                                <div>
                                    <ul>
                                    <?php

                                    if (!isset($_POST['submit_search_user'])) {
                                        $connection = connectDB();

                                        if(isset($_GET['confirme']) AND !empty($_GET['confirme'])) {
                                            $confirme = (int) $_GET['confirme'];

                                            $req = $connection->prepare('UPDATE caf_utilisateur SET etat = 1 WHERE id_client = ?');
                                            $req->execute(array($confirme));
                                        }

                                        if(isset($_GET['supprime']) AND !empty($_GET['supprime'])) {
                                            $supprime = (int) $_GET['supprime'];

                                            $req = $connection->prepare('DELETE FROM caf_utilisateur WHERE id_client = ?');
                                            $req->execute(array($supprime));
                                        }

                                        if(isset($_GET['giveAdmin']) AND !empty($_GET['giveAdmin'])) {
                                            $giveAdmin = (int) $_GET['giveAdmin'];

                                            $req = $connection->prepare('UPDATE caf_utilisateur SET statut = "admin" WHERE id_client = ?');
                                            $req->execute(array($giveAdmin));
                                        }

                                        $utilisateurs = $connection->query('SELECT * FROM caf_utilisateur ORDER BY id_client DESC LIMIT 0,8');


                                    while($utilisateur = $utilisateurs->fetch()) { ?>
                                        <li>
                                            <?php echo $utilisateur['id_client'] ?> :
                                            <b><?php echo $utilisateur['nom'] ?></b>
                                            <div><?php echo $utilisateur['adresse_mail'] ?>
                                            <?php if($utilisateur['etat'] == 0) { ?> -
                                                <a href="admin.php?confirme=<?php echo $utilisateur['id_client'] ?>#users">Confirmer le compte</a>
                                                <?php } ?> -
                                            <a href="admin.php#users?supprime=<?php echo $utilisateur['id_client'] ?>#users">Supprimer le compte</a>
                                            </div>
                                            <?php if($utilisateur['statut'] == "client") { ?>
                                                <a href="admin.php#users?giveAdmin=<?php echo $utilisateur['id_client'] ?>#users">Attribuer des droits administrateurs</a>
                                            <?php } ?>
                                            <hr>
                                        </li>
                                    <?php } ?>
                                    </ul>

                                <?php } elseif (isset($_POST['submit_search_user'])) {

                                    if ($count >= 1) {

                                             echo "$count résultat(s) trouvés pour '<strong>$recherche</strong>' :"; ?> <br>

                                             <br>

                                            <?php  while ($data = $req->fetch(PDO::FETCH_OBJ)) {

                                                echo "Utilisateur : Id = " . $data->id_client . " ; Nom = " . $data->nom . " ; Email = " . $data->adresse_mail;

                                                $userId = $data->id_client;
                                                $userLastname = $data->nom;
                                                $userMail = $data->adresse_mail;
                                                $userEtat = $data->etat;
                                                $userStatut = $data->statut;
                                                ?>
                                                <div>
                                                <?php if($userEtat == 0) { ?> -
                                                    <a href="admin.php?confirme=<?php echo $userId ?>#users">Confirmer le compte</a>
                                                    <?php } ?> -
                                                <a href="admin.php?supprime=<?php echo $userId ?>#users">Supprimer le compte</a>
                                                </div>
                                                <?php if($userStatut == "client") { ?> -
                                                    <a href="admin.php?giveAdmin=<?php echo $userId ?>#users">Attribuer des droits administrateurs</a>
                                                <?php } ?>
                                                <hr>


                                              <?php // attention fin
                                        }
                                      }  else {
                                          echo "\n <hr /> Aucun résultat trouvé pour <strong> $recherche </strong> \n";
                                          ?> <?php
                                        }  }?>
                                </div>
                            </div>
                              <!-- FORFAITS !!!! -->

<!-- FIN FORFAITS !! -->



<!-- CATALOGUE !!!! -->

  <div class="card shadow mb-4" id="catalog" >
      <!-- Card Header - Dropdown -->
      <div
          class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h3 class="m-0 font-weight-bold text-primar chgcolor">Catalogue</h3>


          <form
          class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="POST" action="">
          <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Nom ou description..."
                  aria-label="Rechercher" aria-describedby="basic-addon2" name="recherche">
              <div class="input-group-append">

               <button class="btn btn-secondary" type="SUBMIT" name="submit_search_product" onclick="location.href='admin.php#catalog'">Rechercher un produit
                  </button>
              <?php

              if (isset($_POST['submit_search_product'])) {

                   $connect = connectDB();

                   // Récupère la recherche
                   $recherche = isset($_POST['recherche']) ? $_POST['recherche'] : '';

                   // la requête
                   $req = $connect->query(

                   "SELECT * FROM caf_produit
                    WHERE nom_produit LIKE '%$recherche%'
                    OR description_produit LIKE '%$recherche%'
                    LIMIT 15");


                  $count = $req->rowCount();
              }

              //On traite les résultats

                ?>

              </div>
          </div>
      </form>
          </div>
          <!-- FIIN -->

      <div>
          <ul>
            <?php if(isset($_SESSION["listOfErrorsModifyProducts"])){ ?>
                <div style="background-color:#ad5555; color: white; padding: 10px; margin: 10px; ">
            <?php
                foreach ($_SESSION["listOfErrorsModifyProducts"] as $error) {
                echo "<li>".$error;
                }
                unset($_SESSION["listOfErrorsModifyProducts"]);
            ?>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION["listSuccessModifyProducts"])){ ?>

                <div style="background-color:#46B921; color: white; padding: 10px; margin: 10px; ">
            <?php
                foreach ($_SESSION["listSuccessModifyProducts"] as $success) {
                echo "<li>".$success;
                }
                unset($_SESSION["listSuccessModifyProducts"]);
            ?>
                </div>
            <?php }
                ?>


          <?php

          if (!isset($_POST['submit_search_product'])) {
              $connection = connectDB();

              $produits = $connection->query('SELECT * FROM caf_produit ORDER BY id_produit DESC LIMIT 10');


          while($produit = $produits->fetch()) {
            $idCategory = $produit['id_categorie'];
            $ReqTheCategory = $connection->prepare("SELECT nom_categorie FROM caf_categorie_produit WHERE id_categorie='{$idCategory}'");
            $ReqTheCategory->execute();
            $theCategory = $ReqTheCategory->fetch();
            ?>
              <li>
                  <?php echo $produit['id_produit'] ?> :
                  <b><?php echo $produit['nom_produit'] ?></b> ; <?php echo $produit['prix_produit'] ?>€
                  <div><b><i><?php echo $theCategory['nom_categorie'] ?></i></b></div>
                  <div><?php echo $produit['description_produit'] ?>
                    <!--<img src="data:image/jpeg;base64,<?php //echo base64_encode($produit['photo_produit']); ?>" width="20%" >-->
                    <?php echo '<img src="' . $produit['photo_produit'] .'" width="20%" >'?>

                    <!--<a href="admin.php?modify_product=<?php //echo $produit['id_produit'] ?>#catalog">Modifier le produit</a>-->


              <div class="js-page" id="scroll_modify_product">
                <main class="js-document">
                  <button type="button" aria-haspopup="dialog" aria-controls="dialog">Modifier le produit</button>
                </main>
                <div id="dialog" role="dialog" aria-labelledby="dialog-title" aria-describedby="dialog-desc" aria-modal="true" aria-hidden="true" tabindex="-1" class="c-dialog">
                  <div role="document" class="c-dialog__box">
                    <button type="button" aria-label="Fermer" title="Fermer cette fenêtre modale" data-dismiss="dialog">X</button>
                    <h2 id="dialog-title">Modifier le produit</h2>
                    <p id="dialog-desc">Description du produit</p>
                    <form action="modify_product.php" method="POST" enctype="multipart/form-data">
                      <input type="hidden" id="id_produit" name="id_produit" value="<?php echo $produit['id_produit'];?>">
                      <p>
                        <label for="text">Nom</label><br />
                        <input type="text" id="nom_produit" placeholder="Nom" name="nom_produit" value="<?php echo $produit['nom_produit'];?>" required="required" />
                      </p>
                      <p>
                        <label for="number">Prix</label><br />
                        <input type="number" step="0.01" id="prix_produit" placeholder="Prix" name="prix_produit" value="<?php echo $produit['prix_produit'];?>" required="required" />
                      </p>
                      <p>
                        <label for="text">Description</label><br />
                        <input type="text" id="description_produit" placeholder="Description" name="description_produit" value="<?php echo $produit['description_produit'];?>" required="required" />
                      </p>
                      <p>
                        <label for="file">Photo</label><br />
                        <input type="file" id="photo_produit" name="photo_produit" accept="image/*"/>
                      </p>
                      <p>
                        <button type="submit_modify_product">Valider</button>
                      </p>
                    </form>
                  </div>
                </div>
              </div>
              </div>
              </li>
            <hr>
          <?php } ?>
          </ul>

      <?php } elseif (isset($_POST['submit_search_product'])) {
          if ($count >= 1) {

                   echo "$count résultat(s) trouvés pour '<strong>$recherche</strong>' :"; ?> <br>

                   <br>

                  <?php  while ($data = $req->fetch(PDO::FETCH_OBJ)) {
                    $idCategory = $data->id_categorie;
                    $ReqTheCategory = $connection->prepare("SELECT nom_categorie FROM caf_categorie_produit WHERE id_categorie='{$idCategory}'");
                    $ReqTheCategory->execute();
                    $theCategory = $ReqTheCategory->fetch();
                      echo "Produit : <b>Id = </b>" . $data->id_produit . " ;
                                      <b>Catégorie = </b>" . $theCategory['nom_categorie'] . " ;
                                      <b>Nom = </b>" . $data->nom_produit . " ;
                                      <b>Prix = </b>" . $data->prix_produit . " ;
                                      <b>Description = </b>" . $data->description_produit;

                      ?>
                      <br><?php echo '<img src="' . $data->photo_produit .'" width="20%" >'?>
                      <?php
                      $productId = $data->id_produit;
                      $productCategory = $theCategory['nom_categorie'];
                      $productName = $data->nom_produit;
                      $productPrice = $data->prix_produit;
                      $productDescription = $data->description_produit;
                      $productPhoto = $data->photo_produit;
                      ?>
                      <div>
                      - <a href="admin.php?modify_product=<?php echo $productId ?>#catalog">Modifier le produit</a>
                      <hr>
                    <?php // attention fin
              }
            }  else {
                echo "\n <hr /> Aucun résultat trouvé pour <strong> $recherche </strong> \n";
                ?> <?php
              }  }?>
      </div>
  </div>


    <?php if(isset($_SESSION["listOfErrorsProducts"])){ ?>
        <div style="background-color:#ad5555; color: white; padding: 10px; margin: 10px; ">
    <?php
        foreach ($_SESSION["listOfErrorsProducts"] as $error) {
        echo "<li>".$error;
        }
        unset($_SESSION["listOfErrorsProducts"]);
    ?>
        </div>
    <?php } ?>
    <?php if(isset($_SESSION["listSuccessProducts"])){ ?>

        <div style="background-color:#46B921; color: white; padding: 10px; margin: 10px; ">
    <?php
        foreach ($_SESSION["listSuccessProducts"] as $success) {
        echo "<li>".$success;
        }
        unset($_SESSION["listSuccessProducts"]);
    ?>
        </div>
    <?php }
        ?>
    <p>Ajouter un produit au catalogue</p>
    <form method="POST" enctype="multipart/form-data" action="add_product.php" id="scroll_add_product">
      <div>
        <select name="categorie_produit" id="categorie_produit" required>
          <option value="">Choisir une catégorie</option>
          <?php
          $connection = connectDB();
          $reqCategories = $connection->prepare('SELECT * FROM caf_categorie_produit ORDER BY nom_categorie');
          $reqCategories->execute();
          while($categorie = $reqCategories->fetch()) {
            echo '<option id="categorie_produit" name="categorie_produit">'. $categorie['nom_categorie'] .'</option>';
          } ?>
        </select>
      </div>
      <div>
          <input type="text" id="nom_produit" placeholder="Nom" name="nom_produit" required="required">
      </div>
      <div>
          <input type="number" step="0.01" id="prix_produit" placeholder="Prix" name="prix_produit" required="required">
      </div>
      <div>
          <input type="text" id="description_produit" placeholder="Description" name="description_produit" required="required">
      </div>
      <div>
        <input type="file" id="photo_produit" name="photo_produit" accept="image/*" required="required">
      </div>
      <br>
      <input type="submit" name="submit_add_product" value="Ajouter">
      </input>
      <br>
    </form>

<?php /*
    <div class="js-page">
      <main class="js-document">
        <button type="button" aria-haspopup="dialog" aria-controls="dialog">Modifier le produit</button>
      </main>
      <div id="dialog" role="dialog" aria-labelledby="dialog-title" aria-describedby="dialog-desc" aria-modal="true" aria-hidden="true" tabindex="-1" class="c-dialog">
        <div role="document" class="c-dialog__box">
          <button type="button" aria-label="Fermer" title="Fermer cette fenêtre modale" data-dismiss="dialog">X</button>
          <h2 id="dialog-title">Modifier le produit</h2>
          <p id="dialog-desc">Description du produit</p>
          <form action="" method="POST" enctype="multipart/form-data">
            <p>
              <label for="text">Nom</label><br />
              <input type="text" id="nom_produit" placeholder="Nom" name="nom_produit" value="<?php echo $infos_client['adresse_mail'];?>" required="required" />
            </p>
            <p>
              <label for="number">Prix</label><br />
              <input type="number" step="0.01" id="prix_produit" placeholder="Prix" name="prix_produit" value="<?php echo $infos_client['adresse_mail'];?>" required="required" />
            </p>
            <p>
              <label for="text">Description</label><br />
              <input type="text" id="description_produit" placeholder="Description" name="description_produit" value="<?php echo $infos_client['adresse_mail'];?>" required="required" />
            </p>
            <p>
              <label for="file">Photo</label><br />
              <input type="file" id="photo_produit" name="photo_produit" accept="image/*" required="required" />
            </p>
            <p>
              <button type="submit">Valider</button>
            </p>
          </form>
        </div>
      </div>
    </div> */ ?>
<!-- FIN CATALOGUE !! -->


<br>
<!-- CATEGORIES !!!! -->

  <div class="card shadow mb-4" id="catalog">
      <!-- Card Header - Dropdown -->
      <div
          class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h3 class="m-0 font-weight-bold text-primar chgcolor">Catégories de produits</h3>
          </div>
      <div>
          <ul>
            <?php if(isset($_SESSION["listOfErrorsModifyCategory"])){ ?>
                <div style="background-color:#ad5555; color: white; padding: 10px; margin: 10px; ">
            <?php
                foreach ($_SESSION["listOfErrorsModifyCategory"] as $error) {
                echo "<li>".$error;
                }
                unset($_SESSION["listOfErrorsModifyCategory"]);
            ?>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION["listSuccessModifyCategory"])){ ?>

                <div style="background-color:#46B921; color: white; padding: 10px; margin: 10px; ">
            <?php
                foreach ($_SESSION["listSuccessModifyCategory"] as $success) {
                echo "<li>".$success;
                }
                unset($_SESSION["listSuccessModifyCategory"]);
            ?>
                </div>
            <?php }
                ?>
          <?php
              $connection = connectDB();
              $categories = $connection->query('SELECT * FROM caf_categorie_produit ORDER BY id_categorie DESC LIMIT 10');


          while($category = $categories->fetch()) { ?>
              <li>
                  <?php echo $category['id_categorie'] ?> :
                  <b><?php echo $category['nom_categorie'] ?></b>
                  <div><?php echo $category['description_categorie'] ?>

              <div class="js-page" id="scroll_modify_category">
                <main class="js-document">
                  <button type="button" aria-haspopup="dialog" aria-controls="dialog">Modifier la catégorie</button>
                </main>
                <div id="dialog" role="dialog" aria-labelledby="dialog-title" aria-describedby="dialog-desc" aria-modal="true" aria-hidden="true" tabindex="-1" class="c-dialog">
                  <div role="document" class="c-dialog__box">
                    <button type="button" aria-label="Fermer" title="Fermer cette fenêtre modale" data-dismiss="dialog">X</button>
                    <h2 id="dialog-title">Modifier la catégorie</h2>
                    <p id="dialog-desc">Description de la catégorie</p>
                    <form action="modify_category.php" method="POST">
                      <input type="hidden" id="id_categorie" name="id_categorie" value="<?php echo $category['id_categorie'];?>">
                      <p>
                        <label for="text">Nom</label><br />
                        <input type="text" id="nom_categorie" placeholder="Nom" name="nom_categorie" value="<?php echo $category['nom_categorie'];?>" required="required" />
                      </p>
                      <p>
                        <label for="text">Description</label><br />
                        <input type="text" id="description_categorie" placeholder="Description" name="description_categorie" value="<?php echo $category['description_categorie'];?>" required="required" />
                      </p>
                      <p>
                        <button type="submit_modify_product">Valider</button>
                      </p>
                    </form>
                  </div>
                </div>
              </div>
              </div>
              </li>
            <hr>
          <?php } ?>
          </ul>
      </div>
  </div>


    <?php if(isset($_SESSION["listOfErrorsCategory"])){ ?>
        <div style="background-color:#ad5555; color: white; padding: 10px; margin: 10px; ">
    <?php
        foreach ($_SESSION["listOfErrorsCategory"] as $error) {
        echo "<li>".$error;
        }
        unset($_SESSION["listOfErrorsCategory"]);
    ?>
        </div>
    <?php } ?>
    <?php if(isset($_SESSION["listSuccessCategory"])){ ?>

        <div style="background-color:#46B921; color: white; padding: 10px; margin: 10px; ">
    <?php
        foreach ($_SESSION["listSuccessCategory"] as $success) {
        echo "<li>".$success;
        }
        unset($_SESSION["listSuccessCategory"]);
    ?>
        </div>
    <?php }
        ?>
    <p>Ajouter une catégorie de produit au catalogue</p>
    <form method="POST" action="add_category.php" id="scroll_add_category">
        <div>
            <input type="text" id="nom_categorie" placeholder="Nom" name="nom_categorie" required="required">
        </div>
        <div>
            <input type="text" id="description_categorie" placeholder="Description" name="description_categorie" required="required">
        </div>
        <br>
        <input type="submit" name="submit_add_category" value="Ajouter">
        </input>
        <br>
    </form>
<!-- FIN CATEGORIE !! -->
<br>

<!-- DEBUT COLLECTEURS -->
<div class="card shadow mb-4" id="collectors">
		<!-- Card Header - Dropdown -->
		<div
			class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
			<h3 class="m-0 font-weight-bold text-primar chgcolor">Collecteurs</h3>


			<form
			class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="POST" action="">
			<div class="input-group">
				<input type="text" class="form-control bg-light border-0 small" placeholder="Nom ou email..."
					aria-label="Rechercher" aria-describedby="basic-addon2" name="recherche">
				<div class="input-group-append">

				 <button class="btn btn-secondary" type="SUBMIT" name="submit_search_collector" onclick="location.href='admin.php#collectors'">Rechercher un collecteur
					</button>
				<?php

				if (isset($_POST['submit_search_collector'])) {

					 $connect = connectDB();

					 // Récupère la recherche
					 $recherche = isset($_POST['recherche']) ? $_POST['recherche'] : '';

					 // la requête
					 $req = $connect->query(

					 "SELECT * FROM caf_collecteur
					  WHERE nom LIKE '%$recherche%'
					  OR adresse_mail LIKE '%$recherche%'
					  LIMIT 10");


					$count = $req->rowCount();
				}

				//On traite les résultats

				  ?>

				</div>
			</div>
		</form>
			</div>
			<!-- FIIN -->

		<div>
			<ul>
			<?php

			if (!isset($_POST['submit_search_collector'])) {
				$connection = connectDB();

				if(isset($_GET['supprimeCollecteur']) AND !empty($_GET['supprimeCollecteur'])) {
					$supprime = (int) $_GET['supprimeCollecteur'];

					$req = $connection->prepare('DELETE FROM caf_collecteur WHERE id_collecteur = ?');
					$req->execute(array($supprime));
				}

				$collecteurs = $connection->query('SELECT * FROM caf_collecteur ORDER BY id_collecteur DESC LIMIT 0,8');


			while($collecteur = $collecteurs->fetch()) { ?>
				<li>
					<?php echo $collecteur['id_collecteur'] ?> :
					<b><?php echo $collecteur['nom'] ?></b> <?php echo $collecteur['prenom'] ?>
					<div><?php echo $collecteur['adresse_mail'] ?>
					<a href="admin.php?supprimeCollecteur=<?php echo $collecteur['id_collecteur'] ?>#collectors">Supprimer le compte</a>
					</div>
					<hr>
				</li>
			<?php } ?>
			</ul>

		<?php } elseif (isset($_POST['submit_search_collector'])) {

			if ($count >= 1) {

					 echo "$count résultat(s) trouvés pour '<strong>$recherche</strong>' :"; ?> <br>

					 <br>

					<?php  while ($data = $req->fetch(PDO::FETCH_OBJ)) {

						echo "Collecteur : Id = " . $data->id_collecteur . " ; Nom = " . $data->nom . " ; Prénom = " . $data->prenom . " ; Email = " . $data->adresse_mail;

						$collecteurId = $data->id_collecteur;
						$collecteurLastname = $data->nom;
            $collecteurFirstname = $data->prenom;
						$collecteurMail = $data->adresse_mail;
						?>
						<div>
						<a href="admin.php?supprimeCollecteur=<?php echo $collecteurId ?>#collectors">Supprimer le collecteur</a>
						</div>
						<hr>

					  <?php // attention fin
				}
			  }  else {
				  echo "\n <hr /> Aucun résultat trouvé pour <strong> $recherche </strong> \n";
				  ?> <?php
				}  }?>
		</div>
	</div>
	<!-- AJOUTER COLLECTEUR -->
	    <?php if(isset($_SESSION["listOfErrorsCollector"])){ ?>
        <div style="background-color:#ad5555; color: white; padding: 10px; margin: 10px; ">
    <?php
        foreach ($_SESSION["listOfErrorsCollector"] as $error) {
        echo "<li>".$error;
        }
        unset($_SESSION["listOfErrorsCollector"]);
    ?>
        </div>
    <?php } ?>
    <?php if(isset($_SESSION["listSuccessCollector"])){ ?>

        <div style="background-color:#46B921; color: white; padding: 10px; margin: 10px; ">
    <?php
        foreach ($_SESSION["listSuccessCollector"] as $success) {
        echo "<li>".$success;
        }
        unset($_SESSION["listSuccessCollector"]);
    ?>
        </div>
    <?php }
        ?>

    <p>Ajouter un collecteur</p>
    <form method="POST" action="add_collector.php" id="scroll_add_collector">
        <div>
            <input type="text" id="nom_collecteur" placeholder="Nom" name="nom_collecteur" required="required">
        </div>
		<div>
            <input type="text" id="prenom_collecteur" placeholder="Prénom" name="prenom_collecteur" required="required">
        </div>
		<div>
            <input type="email" id="email_collecteur" placeholder="Adresse mail" name="email_collecteur" required="required">
        </div>
		<div>
            <input type="password" id="mdp_collecteur" placeholder="Mot de passe" name="mdp_collecteur" required="required">
        </div>
        <div>
            <input type="number" id="telephone_collecteur" placeholder="Téléphone" name="telephone_collecteur" required="required">
        </div>
        <br>
        <input type="submit" name="submit_add_collector" value="Ajouter">
        </input>
        <br>
    </form>
    <br>

		<!-- FIN COLLECTEURS -->



      </div>
  </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span> &copy; Trotterfly 2022</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Prêt à partir ?</h5>
                    <a class="btn btn-secondary" href="logout.php">Se déconnecter</a>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
                <!-- Core theme JS-->
                <script src="js/scripts.js"></script>
<?php }
include "footer.php";
?>
