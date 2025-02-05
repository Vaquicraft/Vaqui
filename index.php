	<?php
	session_start();
	if(isset($_SESSION['login']))
		
		{
			header('location: membre.php');
		}
		
		else
			
			{
				include("connexion.php");
			}
		
		?>





