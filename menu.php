<?php

include("css.php");

session_start();
if (isset($_SESSION['login']))
{
	echo $_SESSION['login'];
	echo '<br /> <br />';
	echo '<br /><a href="membre.php">Accueil</a>';
	echo '<br /><a href="perso.php">Persos</a>';
	echo '<br /><a href="histoire.php">Mode Histoire</a>';
	echo '<br /><a href="tchat.php">Tchat</a>';
	echo '<br /><a href="deconnexion.php">Se déconnecter</a>';
}

else
{
	header('location: index.php');
}

?>