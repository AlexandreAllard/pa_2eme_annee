<?php
	session_start();
	require "functions.php";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Se connecter à son compte de Collecteur">
    <meta name="author" content="TROTTERFLY">

    <title>Se connecter</title>
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block" style="background: url('img/collector.jpg'); background-position: center;
													  background-size: cover;"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Prêt à collecter ?</h1>
                                    </div>
                                    <br>
                                    <?php
                                    if(isset($_SESSION["listOfErrors"])){ ?>

                                        <div style="background-color:#ad5555; color: white; padding: 10px; margin: 10px; ">
                                    <?php
                                        foreach ($_SESSION["listOfErrors"] as $error) {
                                        echo "<li>".$error;
                                        }
                                        unset($_SESSION["listOfErrors"]);
                                    ?>
                                        </div>
                                    <?php } ?>
                                    <form method="POST" class="user" action="login_bdd_collector.php">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" aria-describedby="emailHelp"
                                                placeholder="Adresse mail" name="email" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="pwd" placeholder="Mot de passe" name="pwd" required="required">
                                        </div>
                                        <div class="form-group">
                                        </div>
                                        <br>
                                        <input type="submit" value="Se connecter" class="btn btn-primary btn-user btn-block">
                                        </input>
                                        <br>
                                    </form>
                                    <br>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Mot de passe oublié ?</a>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</body>

</html>


<?php
	include "footer.php";
?>
