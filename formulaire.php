<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8" />
		<title> Inscription </title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
	
	<a href="index.php">Retour à l'accueil</a>
	<header>
	<h2>  NarutoProject - Inscription </h2>
	</header>

	<form method="post" action="traitement_inscription.php">
	<div class="inscription">
	
	<input type="text" name="login" placeholder="Pseudo" size="30" required />
	<input type="password" name="password" placeholder="Mot de passe" size="30" required />
	<input type="password" name="passwordverif" placeholder="Confirmer votre mot de passe" size="30" required />
	<input type="mail" name="mail" placeholder="Entrer votre adresse mail" size="30" required />
	<input type="submit" name="Inscription" value="Valider" />
	</form>
	</div>

	</body>
	</html>


















