<?php
require 'functions.php';
bdd_connexion();
check_login();


	$hache = sha1($_POST['passwordco']);			   
	$req=$pdo->prepare('SELECT * FROM users WHERE login=?');
	$req->execute([$_POST['loginco']]);
	$donnees=$req->fetch();
				
	if (!$donnees || !password_verify($_POST['passwordco'], $donnees['password']))
	{
		echo 'Mauvais identifants <html> <br /> <a href="index.php">Retour </html>';
	}
		
	else
	{
		$_SESSION['login'] = $donnees['login'];
		header('location: membre.php');
	}
?>