<?php
require 'menu.php';
require 'functions.php';
bdd_connexion();

echo '<br /><h2>Mode Histoire</h2>';

$req = $pdo->prepare("SELECT * FROM users WHERE login=?");
$req->execute(array($login));
$donnees = $req->fetch();

$user_id = $donnees['user_id'];



check_mh_step();
$req = $pdo->prepare("SELECT * FROM mh_steps WHERE mh_step=?");
$req->execute(array($mh_step));
$donnees = $req->fetch();
echo '<h2><center>Étape ' .$donnees['mh_step'] .' - ' . $donnees['mh_title'] . '</h2></center><br /><br />';
echo $donnees['mh_dialogue'] . '<br /><br />';
    



?>