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

require 'functions.php';
bdd_connexion();
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
			
	$req = $pdo->prepare('INSERT INTO users(login, password, mail, date_inscription, user_mh_step, selected_perso) VALUES(:login, :password, :mail, NOW(), :user_mh_step, :selected_perso)');
	$req->execute(array(
		'login' => $login,
		'password' => $pass_hache,
		'mail' => $mail,
		'user_mh_step' => 1,
		'selected_perso' => "Naruto"
	));


		$req = $pdo->prepare
		('
			INSERT INTO persos (owner_perso, name_perso, xp_perso, nin_perso, tai_perso, gen_perso, life_perso, avatar_perso, win, lose, draw, kills, deaths, training_nin, training_tai, training_gen, training_life)
			VALUES (:owner_perso, :name_perso, :xp_perso, :nin_perso, :tai_perso, :gen_perso, :life_perso, :avatar_perso, :win, :lose, :draw, :kills, :deaths, :training_nin, :training_tai, :training_gen, :training_life)
		');
	
		$req->execute
		([
			'owner_perso' => $login,
			'name_perso' => "Naruto",
			'xp_perso' => 0,
			'nin_perso' => 0,
			'tai_perso' => 0,
			'gen_perso' => 0,
			'life_perso' => 100,
			'avatar_perso' => 1,
			'win' => 0,
			'lose' => 0,
			'draw' => 0,
			'kills' => 0,
			'deaths' => 0,
			'training_nin' => 0,
			'training_tai' => 0,
			'training_gen' => 0,
			'training_life' => 0
		]);

	echo "<html> <center> Votre inscription s'est déroulée avec succès ! </center> <br /> </html>";
	echo '<html> <center><a href="index.php">Cliquez ici pour vous connecter</a></center> <br /> </html>';
}

?>



