<?php
if (session_status() = PHP_SESSION_NONE)
{
	session_start();
}

	if (isset($_SESSION['login']))
	{
		header('location: membre.php');
	}

	if (empty($_POST))
	{
	    header('location: index.php');
	}

	require 'bdd_connexion.php';

	$hache = sha1($_POST['passwordco']);			   
	$req=$pdo->prepare('SELECT * FROM users WHERE login=?');
	$req->execute([$_POST['loginco']]);
	$donnees=$req->fetch();
				
	// if ((empty($donnees['login'])) || $_POST['loginco'] != $donnees['login'] || $hache != $donnees['password'])
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