<?php
if (session_status() == PHP_SESSION_NONE)
{
	session_start();
}
?>

<!DOCTYPE html>
	<html>
	<head>
	<meta charset="utf8" />
	<title> Naruto Project </title>
	<link rel="stylesheet" href="style.css" />
	</head>
	<body>
	
			 <a href="inscription.php">S'inscrire</a>
	
	<header>
<center> <h1> <b> Naruto Project </center> </h1> </b> <br />
	</header>
	
		<body>
	<header>
	<h3> <center> Connexion </h3> </center>
	</header>
	
	
	
	<section>
	<center>
	<form method="post" action="traitement_connexion.php">
	<p><input type="text" name="loginco" placeholder="Pseudo" required /></p>
	<p><input type="password" name="passwordco" placeholder="Mot de passe" required /></p>
	<p><input type="submit" value="Se connecter" /></p>
	<form method="post" action="inscription.php">

	
	</section>
	</body>
		
	<section>	

	</center>

	</body>
	</html>
	
	

	

	
	