<?php
session_start();

require('functions.php');
initSession();


if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
		connexionProcess();	
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


	<h1 class="connexionMainTitle">
		The Wheel of Fight
	</h1>


		<form class="formContent" method="post">
			<h2 class="formSubTitle">
				Connexion
			</h2>

			<div class="usernameForm">
				<label class="formLabel" for="username">Nom d'utilisateur</label>
				<input class="formField" id="username" name="login" type="text" required>
			</div>

			<div class="passwordForm">
				<label class="formLabel" for="password">Mot de Passe</label>
				<input class="formField" id="password" name="pass" type="password" required>
			</div>

			<div class="submitForm">
				<input class ="formSubmit" type="submit" value="Se connecter" />
			</div>

			<?php

				if (!empty($_SESSION['connexionMessage']) && ($_SERVER['REQUEST_METHOD'] == 'POST'))
				{
					echo $_SESSION['connexionMessage'];
					unset($_SESSION['connexionMessage']);
				}

			?>
		</form>
			
</body>
</html>

