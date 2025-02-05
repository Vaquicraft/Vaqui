<?php

session_start();
$_SESSION = array();
session_destroy();

setcookie('login','');
setcookie('password');

echo 'Vous vous êtes bien déconnecté.' . '<a href="index.php">Retour à l\'accueil</a>';
header('location: index.php');


?>