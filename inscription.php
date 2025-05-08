<?php

session_start();

require('functions.php');



if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
		registerProcess();	
    }

initSession();



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

		<form class="formContent" method="post">
			<h2 class="formSubTitle">
				Inscription
			</h2>

			<div class="usernameForm">
				<label class="formLabel" for="username">Nom d'utilisateur</label>
				<input class="formField" id="username" name="login" type="text" required>
			</div>

			<div class="passwordForm">
				<label class="formLabel" for="password">Mot de Passe</label>
				<input class="formField" id="password" name="password" type="password" required>
			</div>

			<div class="passwordConfirmForm">
				<label class="formLabel" for="password">Mot de Passe (confirmation)</label>
				<input class="formField" id="passwordverif" name="passwordverif" type="password" required>
			</div>

			<div class="mailForm">
				<label class="formLabel" for="mail">Adresse Mail</label>
				<input class="formField" id="mail" name="mail" type="text" required>
			</div>

			<div class="submitForm">
				<input class ="formSubmit" type="submit" value="S'inscrire" />
			</div>

			<?php



if(!empty($errors))
{
	?>
	<div class="registerErrorsDisplay">
		<ul>
		<?php

	foreach ($errors as $err)
	{
		?>
		<li>
			<?php
		echo $err;
	}

		?>
</ul>    
</div>
<?php
	
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(!empty($registerSuccess))
?>
<div class="registerInfoDisplay">
<?php
echo $registerSuccess;

}

?>


</div>




		</form>
			



</body>
</html>







	
	
	
	