<?php
require 'functions.php';
menu();
getUserData();

echo '<br /><h2>Mode Histoire</h2>';

  echo '<h2>Étape ' .$dataUser['mh_step'] .' - ' . $dataUser['mh_title'] . '</h2>';
if ($dataUser['mh_perso'] !== $dataUser['selected_perso'])
{
    echo 'Vous devez utiliser ' . $dataUser['mh_perso'] . ' pour cette étape.';
    die;

}

echo $dataUser['mh_dialogue'] . '<br /><br />';
echo '<br /><a href="valider_histoire.php">Combattre !</a>';



?>