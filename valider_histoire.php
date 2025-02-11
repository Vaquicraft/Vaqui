<?php
require 'functions.php';
check_session();
menu();
power($name_perso);
mh_step();
current_perso_stats();

$current_perso = $data_mh_step['mh_perso'];
// $current_perso = $current_perso_data['selected_perso'];
if ($perso !== $current_perso)
{
    echo 'Vous devez utiliser ' . $data_mh_step['mh_perso'] . ' pour cette étape.';
    die;
}


echo '<h1><center>Mode Histoire - Combat</center></h1><br />';
current_mh_perso_stats();
echo '<br /><br /><h2>VS</h2><br />';
enemy_mh_stats();
echo '<br />';
mh_fight_process();

?>