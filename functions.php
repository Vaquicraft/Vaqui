<?php
session_start();

if (!isset($_SESSION['login']))
{
    header('location: index.php');
}
require 'bdd_connexion.php';
$login = $_SESSION['login'];



mh_ini()
{
    $req = $pdo->prepare
    ('
        UPDATE users
        SET mh_step = :mh_step
        WHERE login = :login
    ');
    $req->execute
    ([
        'mh_step' => 1,
        'login' => $login
    ]);
    echo 'Vous venez de débloquer de Mode Histoire !';
}


check_mh_step()
{
     $req = $pdo->prepare("SELECT * FROM users WHERE login=?");
     $req->execute(array($login));
     $donnees = $req->fetch();
     $mh_step = $donnees['mh_step']
     switch($mh_step)
     {
        case 1:
            $target_perso = Naruto;
            break;
        case 2:
            $target_perso = Sakura;
            break;
        case 3:
            $target_perso = Sasuke;
            break;
        case 5:
            $target_perso = Kakashi;
            break;
     }
}




function new_perso()
{
    $target_perso = $value_case;
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
    echo $target_perso . 'a été débloqué !';
}
    

?>