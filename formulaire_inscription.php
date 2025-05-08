<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$registerErrors = $_SESSION['registerErros'] ?? [];
unset($_SESSION['registerErros']);

if(!empty($registerErrors))
{
	foreach ($registerErrors as $err)
	{
		echo $err;
	}
}

?>

