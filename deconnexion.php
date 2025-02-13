<?php

session_start();
$_SESSION = array();
session_destroy();

setcookie('login','');
setcookie('password');

header('location: index.php');


?>