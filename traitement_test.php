<?php
try
{
	$db_config = array();
	$db_config['SGBD']	= 'mysql';
	$db_config['HOST']	= 'localhost';
	$db_config['DB_NAME']	= 'db';
	$db_config['USER']	= 'root';
	$db_config['PASSWORD']	= '';
	$db_config['OPTIONS']	= array(
		// Activation des exceptions PDO :
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		// Change le fetch mode par défaut sur FETCH_ASSOC ( fetch() retournera un tableau associatif ) :
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	);
	
	$pdo = new PDO($db_config['SGBD'] .':host='. $db_config['HOST'] .';dbname='. $db_config['DB_NAME'],
	$db_config['USER'],
	$db_config['PASSWORD'],
	$db_config['OPTIONS']);
	unset($db_config);
}
catch(Exception $e)
{
	trigger_error($e->getMessage(), E_USER_ERROR);
}


$proprio = $_POST['proprio'];

$mailsql = $_POST['proprio'];
		  $req=$pdo->prepare('SELECT * FROM persos WHERE proprio=?');
        $req->execute(array($_POST['proprio']));
        $donnees=$req->fetch();
        if (!empty($donnees))
        {
		echo "Bienvenue ";
		echo $_POST['proprio'];
		echo ". <br />";
		
		}
        else
        {
		echo "Utilisateur inconnu !";
        }
$proprio = $_POST['proprio'];
		
$requete = "SELECT COUNT(proprio), proprio AS proprio FROM persos WHERE proprio= ' .$proprio. '";
$resultat = $pdo->query($requete) or die ('Erreur '.$requete.' '.$mysqli->error());

if echo $resultat = false	

{
	echo "Erreur;";
}

	
	
	
	
	
	
	
	
	
		
//$requete = $bdd->query('SELECT COUNT(proprio) as proprio FROM persos WHERE proprio='. $propriovar .'');
//$nblignes = $requete->fetch();

//if (!empty($nblignes))
//
//echo "Vous possédez ";
//echo $nblignes['proprio'];
//echo " personnages sur votre compte.";




		?>
		

