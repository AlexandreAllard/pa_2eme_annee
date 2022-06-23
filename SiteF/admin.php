<?php
session_start();
//Factorisation
include "header.php";
//require "header.php";
require "functions.php";

if(isset($_SESSION['statut']) != 'admin' || !isset($_SESSION['prenom'])) {

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

    <link rel="stylesheet" href="css/overlay.scss">
    <script type="text/javascript" src="js/overlay.js"></script>

<body>

                <!-- Begin Page Content -->
                <div>
                    <!-- Page Heading -->
                    <div>
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
                                    <h3 class="m-0 font-weight-bold text-primary">Utilisateurs</h3>


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

  <div class="card shadow mb-4" id="catalog">
      <!-- Card Header - Dropdown -->
      <div
          class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h3 class="m-0 font-weight-bold text-primary">Catalogue</h3>


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

              if(isset($_GET['modify_product']) AND !empty($_GET['modify_product'])) {
                //modifier produit
              }

              $produits = $connection->query('SELECT * FROM caf_produit ORDER BY id_produit DESC LIMIT 10');


          while($produit = $produits->fetch()) { ?>
              <li>
                  <?php echo $produit['id_produit'] ?> :
                  <b><?php echo $produit['nom_produit'] ?></b> ; <?php echo $produit['prix_produit'] ?>€
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
                      echo "Produit : <b>Id = </b>" . $data->id_produit . " ;
                                      <b>Nom = </b>" . $data->nom_produit . " ;
                                      <b>Prix = </b>" . $data->prix_produit . " ;
                                      <b>Description = </b>" . $data->description_produit;

                      ?>
                      <br><?php echo '<img src="' . $data->photo_produit .'" width="20%" >'?>
                      <?php
                      $productId = $data->id_produit;
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
    </div>
<!-- FIN CATALOGUE !! -->


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



<?php }
include "footer.php";
?>
