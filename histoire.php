<?php
require 'menu.php';
$login = $_SESSION['login'];

require 'bdd_connexion.php';

echo '<br /><h2>Mode Histoire</h2>';

$req = $pdo->prepare("SELECT * FROM users WHERE login=?");
$req->execute(array($login));
$donnees = $req->fetch();

$user_id = $donnees['user_id'];

if (empty($donnees['mh_step'])) 
    {
    
    $req = $pdo->prepare
    ('
        UPDATE users
        SET mh_step = :mh_step
        WHERE user_id = :user_id
    ');
    $req->execute
    ([
        'mh_step' => 1,
        'user_id' => $user_id
    ]);


    $mh_step = 0;

    // $target_perso = $value_case;
    $req = $pdo->prepare
    ('
        INSERT INTO persos (owner_perso, name_perso, xp_perso, nin_perso, tai_perso, gen_perso, life_perso, avatar_perso, win, lose, draw, kills, deaths, training_nin, training_tai, training_gen, training_life)
        VALUES (:owner_perso, :name_perso, :xp_perso, :nin_perso, :tai_perso, :gen_perso, :life_perso, :avatar_perso, :win, :lose, :draw, :kills, :deaths, :training_nin, :training_tai, :training_gen, :training_life)
    ');

    $req->execute
    ([
        'owner_perso' => $_SESSION['login'],
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
        'training_life' => 0
    ]);


    echo '<br />Naruto a été débloqué !';
    } 

    else 
    {
        $mh_step = $donnees['mh_step'];
        // $req = $pdo->prepare("SELECT * FROM users WHERE login=?");
        // $req->execute(array($login));
        // $donnees = $req->fetch();
        echo '<br />Etape ' . $mh_step;
    }
?>