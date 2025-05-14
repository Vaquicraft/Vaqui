	<?php
	require('functions.php');
	initSession();
	?>

	<!DOCTYPE html>
	<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Accueil</title>
		<link rel="stylesheet" href="newstyle.css" />
	</head>
	<body>
		<header class="header">
		<ul class="headerMenu">
			<li class="headerMenuConnexion">
				<a href="connexion.php">Connexion</a>
			</li>

			<li class="headerMenuRegister">
				<a href="inscription.php">S'inscrire</a>
			</li>

			<li class="headerMenuAbout">
				<a href="about.php">À Propos</a>
			</li>
		</ul>
	</header>

	<div class="homeMessage">
		<h1 class="connexionMainTitle">
			The Wheel of Fight
		</h1>
		<p class ="mainText">
		
			Connectez-vous ou créez un compte pour pouvoir jouer au meilleur jeu par navigateur du moment, selon une étude réalisée sur 1 personne (moi-même)
		</p>
	</div>

	</body>
	</html>





