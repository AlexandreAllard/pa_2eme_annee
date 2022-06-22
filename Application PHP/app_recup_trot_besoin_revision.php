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

$trottinettes = $connection->query('SELECT * FROM caf_trottinette WHERE etat_trottinette = 1 ');

echo "Besoin de révision (+150km) : ";

while($trottinette = $trottinettes->fetch()) { ?>
    <li>

        <?php echo $trottinette['id_trottinette'] ?> :
        <?php echo $trottinette['etat_trottinette'] ?>
        <?php echo $trottinette['coordonnees'] ?>
        <?php echo $trottinette['km']."km" ?>
        <hr>
    </li>
<?php } ?>
