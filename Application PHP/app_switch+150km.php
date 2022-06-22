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
$connection = connectDB();

$trottinettes = $connection->query('SELECT * FROM caf_trottinette ');

while($trottinette = $trottinettes->fetch()) { ?>
    <li>
        <?php $updateStatus = $connection->prepare("UPDATE `caf_trottinette` SET `etat_trottinette`= 1 WHERE km>=150 ");
        $updateStatus->execute();
        //a faire : passer en modulo 150, garder le km total
        ?>

        <?php echo "id : ".$trottinette['id_trottinette'] ?><br>
        <?php echo "etat_trottinette : " . $trottinette['etat_trottinette'] ?><br>
        <?php echo "coordonnees : " . $trottinette['latitude'] . ", " . $trottinette['longitude'] ?><br>
        <?php echo "Distance parcourue totale : " . $trottinette['km']."km" ?>
        <hr>
    </li>
<?php } ?>
