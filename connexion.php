<?php
if (session_status() == PHP_SESSION_NONE)
{
	session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>The Wheel of Fight</title>
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

	<div class="connexionMain">
		<h1 class="connexionMainTitle">
			The Wheel of Fight
		</h1>

		

	</div>

	<div class="connexionForm">
		<h2 class="connexionSubTitle">
			Connexion
		</h2>
		<form method="post" action="traitement_connexion.php"></form>

		<div class="connexionUsernameForm">
			<label class="formLabel" for="username">Nom d'utilisateur</label>
			<input class="formField" id="username" type="text" required>
		</div>

		<div class="connexionPasswordForm">
			<label class="formLabel" for="password">Mot de Passe</label>
			<input class="formField" id="password" type="password" required>
		</div>

		<div class="connexionSubmitForm">
			<input class ="formSubmit" type="submit" value="Se connecter" />
		</div>	

	</div>

</body>
</html>

	

	

	
	