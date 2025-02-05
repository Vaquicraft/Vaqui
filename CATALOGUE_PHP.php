<?php 

					// Catalogue de toutes les commandes PHP

$error = false; // Teste la présence d'erreur (utile pour les vérifications)

if $error = true;
{
	// Si il y a une erreur, executer le code suivant
}

else
{
	// Sinon, executer ce code.
}



//////////////////////////////////////////////////////////////////////////////////////

					// Connexion base de données

try 
	{

	$bdd = new PDO('mysql:host=localhost;dbname=db;charset=utf8', 'root', '');
	
	}
	// S'il y a une erreur lors de la connexion à la base :
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
		
	}
	
//////////////////////////////////////////////////////////////////////////////////////



					// Vérifications des informations


					
					// Test de longueur de caractères 

if (strlen($_POST['valeur']) > 3)
	{
	// Si la valeur testée est supérieur à 3, on execute le code suivant 
	}
else
	{
	// Sinon, on execute ce code 
	}
	
//////////////////////////////////////////////////////////////////////////////////////
	
	
	
					// Vérification de l'existence d'une information dans une base de donnée 

$mailsql = $_POST['valeur'];
		  $req=$bdd->prepare('SELECT * FROM table WHERE valeur=?');
        $req->execute(array($_POST['valeur']));
        $donnees=$req->fetch();
        if (!empty($donnees))// Si l'entrée du tableau données n'est pas vide :
        {
		// Executer ce code 
		}
        else
        {
		// Sinon, executer ce code 
        }
		
		
		
//////////////////////////////////////////////////////////////////////////////////////	
		
		
					// Vérification de la correspondance entre deux informations 

 if ($_POST['valeur'] == $_POST['valeur2'])
		{
		// Executer ce code 
		}
	else
		{
		// Sinon, on execute ce code 
		}
		
//////////////////////////////////////////////////////////////////////////////////////		
		
		
		
					// Vérification de la validité d'une adresse mail

if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['mail']))
		{
		// Si l'adresse mail est valide, on execute ce code 
		}
	else
		{
		// Si l'adresse mail n'est pas valide, alors on execute ce code
		}
		
//////////////////////////////////////////////////////////////////////////////////////



					// Hachage du mot de passe (obligatoire pour tout système d'inscription)

$pass_hache = sha1($_POST['password']); // Prendre $pass_hache pour l'insertion d'information dans la base de données 

//////////////////////////////////

					// Récupération des informations pour ensuite les injecter dans la base de données (inscription)

if ( !$error ) // S'il n'y a pas d'erreur

{
	
$login = $_POST['login'];
$password = $_POST['password'];
$passwordverif = $_POST['passwordverif'];
$mail = $_POST['mail'];

}

///////////////////////////////////////////////////////////////////////


					// Insertion des informations dans la base de donnée
					
	

if ( ! $error )  // S'il n'y a pas d'erreur 
	
	{
	
$req = $bdd->prepare('INSERT INTO TABLE(login, password, mail, date_inscription) VALUES(:login, :password, :mail, NOW())'); // NOW() correspond au format DATE_TIME (date+heure) 
$req->execute(array(
    'login' => $login,
    'password' => $pass_hache, //Il est important de prendre la variable du mot de passe haché
    'mail' => $mail));
	
	// Tout le code ci-dessus sera executé 
	}
	
else 
{
	// S'il y a une erreur, executer ce code 
}

/////////////////////////////////////////////////////////////////

// Récupération du contenu
$reponse = $bdd->query('SELECT * FROM persos');

// On affiche chaque entrée une à une
while ($donnees = $reponse->fetch())
{
	echo $donnees['proprio'];

}

$reponse->closeCursor(); // Termine le traitement de la requête

/////////////////////////////////////////////////////////////////////////////////


// Compter le nombre de lignes que possède un élément
$requete = $bdd->query('SELECT COUNT(proprio) as proprio FROM persos WHERE proprio=\'Joueur\'');
$nblignes = $requete->fetch();
echo $nblignes['proprio'];

//////////////////////////////////////////////////////////////////////////////





	





