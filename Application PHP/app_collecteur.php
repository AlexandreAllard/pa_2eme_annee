
<?php
/*
etat_trottinette
0 = fonctionnel
1 = besoin de révision (+150km)
2 = besoin de reparation (cassée)
3 = en reparation
4 = hors service (poubelle)
*/

require "functions.php";
if (isset($_POST["submit_lister"])) {
  $connection = connectDB();
  $trottinettes = $connection->query('SELECT * FROM caf_trottinette WHERE etat_trottinette = 1 ');
  echo "Besoin de révision (+150km) : ";
  while($trottinette = $trottinettes->fetch()) { ?>
      <li>

          <?php echo "id : ".$trottinette['id_trottinette'] ?><br>
          <?php echo "etat_trottinette : " . $trottinette['etat_trottinette'] ?><br>
          <?php echo "coordonnees : " . $trottinette['latitude'] . ", " . $trottinette['longitude'] ?><br>
          <?php echo "Distance parcourue totale : " . $trottinette['km']."km" ?>
          <hr>
      </li>
  <?php }
}

?><br><?php

if (isset($_POST["submit_lister"])) {
  $connection = connectDB();
  $trottinettes = $connection->query('SELECT * FROM caf_trottinette WHERE etat_trottinette = 2 ');
  echo "Besoin de réparation : ";
  while($trottinette = $trottinettes->fetch()) { ?>
      <li>

          <?php echo $trottinette['id_trottinette'] ?> :
          <?php echo $trottinette['etat_trottinette'] ?>
          <?php echo $trottinette['latitude'] . ", " . $trottinette['longitude'] ?>
          <?php echo $trottinette['km']."km" ?>
          <hr>
      </li>
  <?php }
}



if (isset($_POST["submit_recup_trot"])) {
$id=$_POST["id_trottinette"];
  $connection = connectDB();
  $updateStatus = $connection->prepare("UPDATE `caf_trottinette` SET `etat_trottinette`= 3 WHERE id_trottinette=:id_trottinette ");
  $updateStatus->execute(["id_trottinette"=>$id]);
  echo "Trottinette ".$id." passée en réparation";
}

if (isset($_POST["submit_mise_service_trot"])) {
$id=$_POST["id_trottinette"];
  $connection = connectDB();
  $updateStatus = $connection->prepare("UPDATE `caf_trottinette` SET `etat_trottinette`= 0 WHERE id_trottinette=:id_trottinette ");
  $updateStatus->execute(["id_trottinette"=>$id]);
  echo "Trottinette ".$id." remise en circulation";
}

//fin 1ere balise php?>

<form method="POST" action="">
    <input type="submit" value="Lister les trottinettes qui ont besoin de revision ou réparation" name="submit_lister" > </input>
</form>

<form method="POST" action="">

    <div class="form-group">

        <input type="number"
            id="id_trottinette"
            placeholder="id trottinette" name="id_trottinette" required>

        <input type="submit" value="Récuperer la trottinette" name="submit_recup_trot">
        </input>
    </div>
</form>

<br>

<form method="POST" action="">

    <div class="form-group">

        <input type="number"
            id="id_trottinette"
            placeholder="id trottinette" name="id_trottinette" required>

        <input type="submit" value="Mettre la trottinette en service" name="submit_mise_service_trot">
        </input>
    </div>
    <br>
</form>
