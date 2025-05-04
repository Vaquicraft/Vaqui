<?php


if (isset($_SESSION['login']))
{
	header('location: membre.php');
}

else
{
	require 'formulaire_connexion.php';
}

?>

	

	

	
	