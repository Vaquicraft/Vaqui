<?php
require 'functions.php';
check_login();
bdd_connexion();
menu();
power();
mh_step();
current_perso_stats();

$perso = $data_mh_step['mh_perso'];
$current_perso = $current_perso_data['selected_perso'];
if ($perso !== $current_perso)
{
    echo 'Vous devez utiliser ' . $perso . ' pour cette étape.';
    die;
}


echo '<h1><center>Mode Histoire - Combat</center></h1><br />';
current_mh_perso_stats();
echo '<br /><br /><h2>VS</h2><br />';
enemy_mh_stats();
echo '<br />';
mh_fight_process();

?>