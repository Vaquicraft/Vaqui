<?php

session_start();

if (isset($_SESSION['login']))
{
	header('location: membre.php');
}

if (empty($_POST))
{
	header('location: inscription.php');
}

require 'bdd_connexion.php';
require 'inscription.php';


$req=$pdo->prepare("SELECT * FROM users WHERE login=?");
$req->execute(array($_POST['login']));
$donnees=$req->fetch();
if (!empty($donnees))
{
	echo "<html> <center> Ce nom d'utilisateur est déjà utilisé. </center> <br /> </html>";
	die;
}

$req=$pdo->prepare("SELECT * FROM users WHERE mail=?");
$req->execute(array($_POST['mail']));
$donnees=$req->fetch();
if (!empty($donnees))
{
	echo "<html> <center> Cette adresse mail est déjà utilisée. </center> <br /> </html>";
	die;
}

if (strlen($_POST['login']) <= 3)
{
	echo "<html> <center> Votre pseudo est trop court. (4 caractères minimum). </center> <br /> </html>";
	die;
}

if (strlen($_POST['password']) <= 6 || $_POST['password'] != $_POST['passwordverif'])
{
	echo "<html> <center> Votre mot de passe invalide ou trop court. (7 caractères minimum). </center> <br /> </html>";
	die;
}

if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['mail']))
{
	echo "<html> <center> Votre adresse mail est invalide. </center> <br /> </html>";
	die;
}

else
{
	$pass_hache = password_hash($_POST['password'], PASSWORD_DEFAULT);

	$login = $_POST['login'];
	$password = $_POST['password'];
	$passwordverif = $_POST['passwordverif'];
	$mail = $_POST['mail'];
			
	$req = $pdo->prepare('INSERT INTO users(login, password, mail, date_inscription) VALUES(:login, :password, :mail, NOW())');
	$req->execute(array(
		'login' => $login,
		'password' => $pass_hache,
		'mail' => $mail));

	echo "<html> <center> Votre inscription s'est déroulée avec succès ! </center> <br /> </html>";
	echo '<html> <center><a href="index.php">Cliquez ici pour vous connecter</a></center> <br /> </html>';
}

?>



