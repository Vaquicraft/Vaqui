a<?php
session_start();

	if (isset($_SESSION['login']))
	{
		header('location: membre.php');
	}

	if (empty($_POST))
	{
	    header('location: index.php');
	}

	else
  	{
	
		include("bdd_connexion.php");

		$hache = sha1($_POST['passwordco']);			   
		$req=$pdo->prepare('SELECT * FROM users WHERE login=?');
		$req->execute(array($_POST['loginco']));
		$donnees=$req->fetch();

					


		
					
		if ((empty($donnees['login'])) || $_POST['loginco'] != $donnees['login'] || $hache != $donnees['password'])
		{
			echo "Mauvais identifants";
			echo '<html><a>href="index.php">Retour</a></html>';
			die;
		}
			
		else
		{

			$_SESSION['login'] = $donnees['login'];
			header('location: membre.php');
		}
		
	}	
?><!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8" />
		<title> Page de test </title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
	
	<a href="index.php">Retour à l'accueil</a>
	<header>
	<h1> <center> Connexion premium </h1> </center> <br /> <br /> <br />
	<center>
	</header>
	
	

	
	<section>
	<center>
	
	
	
<form method="post" action="traitement_test.php">

<p> <input type="text" name="proprio" placeholder="Pseudo" size="30" required /> </p>
<p> <input type="submit" name="Inscription" value="Valider" /> </p>


	</section>
	</form>
	<center>
		<form method="post" action="index.php">

	</center>
	</center>

	</body>
	</html>
	

</form>
