<?php
function check_login()
{
    global $login;
    if (session_status() == PHP_SESSION_NONE)
        {
        session_start();
            if (!isset($_SESSION['login']))
            {
            header('location: index.php');
            $login = $_SESSION['login'];
            }
    }   

}

function bdd_connexion()
{
    global $pdo;
    try
  {
    $pdo = new PDO('mysql:host=localhost;dbname=db;charset=utf8', 'root', '');
  }
catch(Exception $e)
  {
    die('Erreur : '.$e->getMessage());   
  }
}


function menu()
{
    echo '<br /> <br />';
	echo '<br /><a href="membre.php">Accueil</a>';
	echo '<br /><a href="perso.php">Persos</a>';
	echo '<br /><a href="histoire.php">Mode Histoire</a>';
	echo '<br /><a href="tchat.php">Tchat</a>';
	echo '<br /><a href="deconnexion.php">Se déconnecter</a>';
}
	

function mh_step()
{
    global $mh_step, $pdo, $login;
    check_login();
    bdd_connexion();
    $req=$pdo->prepare("SELECT * FROM users WHERE login=?");
    $req->execute(array($login));
    $donnees=$req->fetch();
    $mh_step = $donnees['mh_step'];
}

function get_data_current_step()
{
    global $data_mh, $pdo, $login, $data_step;
    bdd_connexion();
    mh_step();
    $req = $pdo->prepare("SELECT * FROM mh_steps WHERE mh_step=?");
    $req->execute(array($data_step));
    $data_mh = $req->fetch();
}

function get_new_perso()
{
    global $new_perso, $data_mh;
    get_data_current_step();
}


function new_perso()
{
    global $pdo, $login, $new_perso, $data_mh;
    bdd_connexion();
    get_new_perso();   
    $req = $pdo->prepare
    ('
        INSERT INTO persos (owner_perso, name_perso, xp_perso, nin_perso, tai_perso, gen_perso, life_perso, avatar_perso, win, lose, draw, kills, deaths, training_nin, training_tai, training_gen, training_life)
        VALUES (:owner_perso, :name_perso, :xp_perso, :nin_perso, :tai_perso, :gen_perso, :life_perso, :avatar_perso, :win, :lose, :draw, :kills, :deaths, :training_nin, :training_tai, :training_gen, :training_life)
    ');

    $req->execute
    ([
        'owner_perso' => $login,
        'name_perso' => $new_perso,
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
    echo $new_perso . ' a été débloqué !<br />';
}
    
?>