<?php

if (isset($_SESSION['login']))
{
	header('location: membre.php');
}


require 'functions.php';
check_login();
bdd_connexion();
require 'connexion.php';

	$hache = sha1($_POST['pass']);			   
	$req=$pdo->prepare('SELECT * FROM users WHERE login=?');
	$req->execute([$_POST['login']]);
	$donnees=$req->fetch();
				
	if (!$donnees || !password_verify($_POST['pass'], $donnees['password']))
	{
		echo 'Mauvais identifants';
		die;
	}
		
	else
	{
		$_SESSION['login'] = $donnees['login'];
		header('location: membre.php');
	}
?>