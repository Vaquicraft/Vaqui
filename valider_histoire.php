<?php
require 'functions.php';
check_login();
bdd_connexion();
menu();
power();
mh_step();
current_perso_stats();

$req = $pdo->prepare("
SELECT users.*, mh_steps.*
FROM users
INNER JOIN mh_steps ON users.selected_perso= mh_steps.mh_perso
WHERE users.login = ?
");
$req->execute([($login)]);
$data_selected_perso = $req->fetch();
$data_selected_perso = $data_selected_perso['selected_perso'];
if (empty($data_selected_perso))
{
    echo 'Vous devez utiliser ' . $data_selected_perso . ' pour cette étape.';
    die;
}


echo '<h1><center>Mode Histoire - Combat</center></h1><br />';
current_mh_perso_stats();
echo '<br /><br /><h2>VS</h2><br />';
enemy_mh_stats();
echo '<br />';
mh_fight_process();

?>