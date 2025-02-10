<?php
require 'functions.php';
check_login();
bdd_connexion();
menu();
mh_step();
current_perso_stats();

echo '<br /><h2>Mode Histoire</h2>';

echo '<h2><center>Étape ' .$data_mh_step['mh_step'] .' - ' . $data_mh_step['mh_title'] . '</h2></center><br /><br />';
if ($data_mh_step['mh_perso'] !== $data_mh_step['selected_perso'])
{
    echo 'Vous devez utiliser ' . $data_mh_step['mh_perso'] . ' pour cette étape.';
    die;

}

echo $data_mh_step['mh_dialogue'] . '<br /><br />';
echo '<br /><a href="valider_histoire.php">Combattre !</a>';
    



?>