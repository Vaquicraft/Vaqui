<?php
require 'menu.php';
require 'functions.php';
check_login();
bdd_connexion();

echo '<br /><h2>Mode Histoire</h2>';
echo "$login";

$req = $pdo->prepare("SELECT * FROM users WHERE login=?");
$req->execute(array($login));
$donnees = $req->fetch();
$mh_step = $donnees['mh_step'];




$req = $pdo->prepare("SELECT * FROM mh_steps WHERE mh_step=?");
$req->execute(array($mh_step));
$donnees = $req->fetch();
echo '<h2><center>Étape ' .$donnees['mh_step'] .' - ' . $donnees['mh_title'] . '</h2></center><br /><br />';
echo $donnees['mh_dialogue'] . '<br /><br />';
    



?>