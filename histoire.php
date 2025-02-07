<?php
require 'menu.php';
$login = $_SESSION['login'];

require 'bdd_connexion.php';

echo '<br /><h2>Mode Histoire</h2>';

$req = $pdo->prepare("SELECT * FROM users WHERE login=?");
$req->execute(array($login));
$donnees = $req->fetch();

$user_id = $donnees['user_id'];

if (empty($donnees['mh_step'])) 
    {
        require 'functions.php';
        mh_ini();
        check_mh_step();
        new_perso();
    }
    
 


?>