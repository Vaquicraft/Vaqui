<?php
require 'menu.php';
$login = $_SESSION['login'];
echo  '<h2><br /><br /><br /><br />Gestion des personnages de ' . $login . '<br /></h2>';
require 'bdd_connexion.php';
$req=$pdo->prepare("SELECT * FROM users WHERE login=?");
$req->execute(array($login));
$donnees=$req->fetch();

$user_id = $donnees['user_id'];
$target_perso = 'naruto';

$req=$pdo->prepare("SELECT * FROM persos WHERE id_owner=?");
$req->execute(array($user_id));
$donnees=$req->fetch();
  if($donnees)
  {
    if(empty($donnees['id_owner']) && (empty($donnees['name_perso'])))
    {
        echo "Aucune donnée.";
        
        
    }
    echo '<br />Nom du perso : ' . ucfirst($donnees['name_perso']);
    echo '<br />Expérience : ' . $donnees['xp_perso'];    
    echo '<br />Ninjutsu : ' . $donnees['nin_perso'];
        echo '<br />Taijutsu : ' . $donnees['tai_perso'];
        echo '<br />Genjutsu : ' . $donnees['gen_perso'];
    
  }
  
?>