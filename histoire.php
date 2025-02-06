<?php


require 'menu.php';
$login = $_SESSION['login'];

require 'bdd_connexion.php';


$req=$pdo->prepare("SELECT * FROM users WHERE login=?");
$req->execute(array($login));
$donnees=$req->fetch();

$user_id = $donnees['user_id'];
if(empty($donnees['mh_step']))
{
    $mh_step = 0;
    echo '<br />Vous ne possédez pas encore de personnage.';
    $req = $pdo->prepare('INSERT INTO persos (id_owner, name_perso, xp_perso, nin_perso, tai_perso, gen_perso, life_perso, avatar_perso, win, lose, draw, kills, deaths, training_nin, training_tai, training_gen, training_life ) VALUES (:id_owner, :name_perso, :xp_perso, :nin_perso, :tai_perso, :gen_perso, :life_perso, :avatar_perso, :win, :lose, :draw, :kills, :deaths, :training_nin, :training_tai, :training_gen, :training_life'));
	$req->execute([
		'id_owner' => intval($user_id),
		'name_perso' => 'Naruto',
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
		'training_life' => 0]);
    echo '<br />Naruto a été débloqué !';
}
else
{
    $mh_step = $donnees['mh_step'];
}



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