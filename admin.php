<?php
session_start();
//Factorisation
include "header.php";
//require "header.php";
require "functions.php";

if(isset($_SESSION['statut']) == 'client' || !isset($_SESSION['prenom'])) {

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
                            <div class="card shadow mb-4">
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

                                         <button class="btn btn-secondary" type="SUBMIT" name="submit">Rechercher un utilisateur
                                            </button>
                                        <?php

                                        if (isset($_POST['submit'])) {

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

                                    if (!isset($_POST['submit'])) {
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
                                                <a href="admin.php?confirme=<?php echo $utilisateur['id_client'] ?>">Confirmer le compte</a>
                                                <?php } ?> -
                                            <a href="admin.php?supprime=<?php echo $utilisateur['id_client'] ?>">Supprimer le compte</a>
                                            </div>
                                            <?php if($utilisateur['statut'] == "client") { ?>
                                                <a href="admin.php?giveAdmin=<?php echo $utilisateur['id_client'] ?>">Attribuer des droits administrateurs</a>
                                            <?php } ?>
                                            <hr>
                                        </li>
                                    <?php } ?>
                                    </ul>

                                <?php } elseif (isset($_POST['submit'])) {

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
                                                    <a href="admin.php?confirme=<?php echo $userId ?>">Confirmer le compte</a>
                                                    <?php } ?> -
                                                <a href="admin.php?supprime=<?php echo $userId ?>">Supprimer le compte</a>
                                                </div>
                                                <?php if($userStatut == "client") { ?> -
                                                    <a href="admin.php?giveAdmin=<?php echo $userId ?>">Attribuer des droits administrateurs</a>
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
                    <a class="btn btn-primary" href="logout.php">Se déconnecter</a>
                </div>



<?php }
include "footer.php";
?>
