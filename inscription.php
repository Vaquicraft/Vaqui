	<?php
	
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}
	if(isset($_SESSION['login']))	
		{
			header('location: membre.php');
		}	
	else		
		{
			require 'formulaire_inscription.php';
		}	
	?>



	
	
	
	