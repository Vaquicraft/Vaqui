<?php
function check_login()
{
    global $login;
    if (session_status() == PHP_SESSION_NONE)
        {
        session_start();
        $login = $_SESSION['login'];
            if (!isset($_SESSION['login']))
            {
            header('location: index.php');
            
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
    echo $_SESSION['login'];
    current_perso_stats();
    echo'<html> <link rel="stylesheet" href="style.css" /> <title> Naruto Project </title> </html>';
    echo '<br /> <br />';
	echo '<br /><a href="membre.php">Accueil</a>';
	echo '<br /><a href="perso.php">Persos</a>';
	echo '<br /><a href="histoire.php">Mode Histoire</a>';
    echo '<br /><a href="training.php">Entraînements</a>';
	echo '<br /><a href="tchat.php">Tchat</a>';
	echo '<br /><a href="deconnexion.php">Se déconnecter</a><br /><br />';
    
}
	

function mh_step()
{
    global $data_mh_step, $pdo, $login;
    check_login();
    bdd_connexion();

    $req = $pdo->prepare("
    SELECT users.*, mh_steps.*
    FROM users
    INNER JOIN mh_steps ON users.mh_step = mh_steps.mh_step
    WHERE users.login = ?
    ");
    $req->execute(array($login));
    $data_mh_step = $req->fetch();
}

function new_perso()
{
    global $pdo, $login, $data_mh_step;
    check_login();
    bdd_connexion();
    mh_step();
  
    $req = $pdo->prepare
    ('
        INSERT INTO persos (owner_perso, name_perso, xp_perso, nin_perso, tai_perso, gen_perso, life_perso, avatar_perso, win, lose, draw, kills, deaths, training_nin, training_tai, training_gen, training_life)
        VALUES (:owner_perso, :name_perso, :xp_perso, :nin_perso, :tai_perso, :gen_perso, :life_perso, :avatar_perso, :win, :lose, :draw, :kills, :deaths, :training_nin, :training_tai, :training_gen, :training_life)
    ');

    $req->execute
    ([
        'owner_perso' => $login,
        'name_perso' => $data_mh_step['mh_reward_1'],
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
}

function power()
{
    check_login();
    bdd_connexion();
    global $pdo, $login, $power, $data, $perso;

    $req=$pdo->prepare("SELECT * FROM persos WHERE owner_perso=?");
    $req->execute(array($login));
    $data=$req->fetch();
    $pts_nin = $data['nin_perso'] * 1.1;
    $pts_tai = $data['tai_perso'] * 1.1;
    $pts_gen = $data['gen_perso'] * 1.1;
    $pts_life = $data['life_perso'] * 0.01;
    $power = $pts_nin + $pts_tai + $pts_gen + $pts_life;
    $power = $power + 10;
    return $power;
}








function current_perso_stats()
{
    global $current_perso_data, $pdo, $login, $power, $perso;
    check_login();
    bdd_connexion();
    power();
    
    $req = $pdo->prepare("
    SELECT users.*, persos.*
    FROM users
    INNER JOIN persos ON users.login = persos.owner_perso
    WHERE users.login = ?
    ");
    $req->execute(array($login));
    $perso = $req->fetch();

    perso_stats();
}

function list_perso_stats()
{
    global $pdo, $login, $perso, $persos;
    check_login();
    bdd_connexion();
    $req=$pdo->prepare("SELECT * FROM persos WHERE owner_perso=?");
    $req->execute([($login)]);
    $persos=$req->fetchAll();

}





function perso_stats()
{
    global $login, $pdo, $data_mh_step, $power, $data_list_persos, $perso;
    check_login();
    bdd_connexion();
    mh_step();
    echo '<br /><br /><br /><br /><b>' . $perso['selected_perso'] . '</b>';    
    echo '<br />Puissance : ' . $power;  
    echo '<br />Ninjutsu : ' . $perso['nin_perso'];
    echo '<br />Taijutsu : ' . $perso['tai_perso'];
    echo '<br />Genjutsu : ' . $perso['gen_perso'];
    echo '<br />Vie : ' . $perso['life_perso'];
    echo '<br /><br />';
}












function current_mh_perso_stats()
{
    global $current_perso_data, $pdo, $login, $power, $data_mh_step;
    check_login();
    bdd_connexion();
    power();
    mh_step();


    echo '' . $data_mh_step['mh_perso'];    
    echo '<br />Puissance : ' . $power;  
    
}

function enemy_mh_stats()
{
    global $enemy, $pdo, $login;
    check_login();
    bdd_connexion();
    
    $req = $pdo->prepare("
    SELECT users.*, mh_steps.*
    FROM users
    INNER JOIN mh_steps ON users.mh_step = mh_steps.mh_step
    WHERE users.login = ?
    ");
    $req->execute([($login)]);
    $enemy = $req->fetch();
    echo '' . $enemy['mh_enemy'];    
    echo '<br />Puissance : ' . $enemy['mh_power'];
    echo '<br /><br />';
}


function mh_fight_process()
{
    global $pdo, $login, $power, $current_perso_mh_data, $enemy;
    check_login();
    bdd_connexion();
    mh_step();
    power();
    if ($power >= $enemy['mh_power'])
    {
        echo 'Vous avez remporté le combat !<br /><br />';
        mh_process_win();
    }
    else
    {
        echo 'Vous avez perdu le combat...';
    }

}

function mh_process_win()
{
    global $login, $pdo, $reward1, $reward2, $reward3, $data_mh_step;
    check_login();
    bdd_connexion();
    mh_step();
    $reward1 = $data_mh_step['mh_reward_1'];
    echo 'Vous avez obtenu le personnage ' . $reward1;
    new_perso();
    $req = $pdo->prepare("UPDATE users SET mh_step = mh_step + 1 WHERE login = ?");
    $req->execute([($login)]);
    

}




?>
