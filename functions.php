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

function check_session()
{
    global $selected_perso, $pdo, $req, $data, $login;
    check_login();
    bdd_connexion();
    if (isset($_SESSION['selected_perso']))
            {
                $selected_perso = $_SESSION['selected_perso'];
            }
    else
    {
        $req = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $req->execute([$login]);
        $data = $req->fetch();
        $name_perso = $data['selected_perso'];
        $_SESSION['selected_perso'] = $name_perso;
    }
}


function getUserData()
{
    global $login, $pdo, $user_data;
    check_session();
    $req = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $req->execute([$login]);
    $user_data = $req->fetch();
}


function menu()
{
    global $selected_perso, $req, $pdo, $perso;
    check_session();
    global $pdo, $req, $perso, $name_perso, $login;
    $req = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $req->execute([$login]);
    $perso = $req->fetch();
    $perso = $perso['selected_perso'];
    include('base.html');
    current_perso_stats();
}
	

function mh_step()
{
    global $data_mh_step, $pdo, $login, $user_data;
    check_session();
    getUserData();

    if (!isset($user_data) || !isset($user_data['user_mh_step'])) {
        die("Erreur : user_mh_step non défini dans user_data.");
    }

    $req = $pdo->prepare("
    SELECT users.*, mh_steps.*, persos.*
    FROM users
    INNER JOIN mh_steps ON users.user_mh_step = mh_steps.mh_step
    INNER JOIN persos ON users.login = persos.owner_perso
    WHERE users.login = ? AND users.user_mh_step = ?
    ");
    $req->execute(array($login, $user_data['user_mh_step']));
    $data_mh_step = $req->fetch();

    if (!$data_mh_step)
    {
        echo '<br /><h2>Mode Histoire</h2>';
        die("La suite très bientôt.. Ou pas !");
    }
}

function new_perso()
{
    global $pdo, $login, $data_mh_step;
    check_session();
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

function power($name_perso)
{
    check_session();
    global $pdo, $login, $power, $data, $perso, $name_perso;

    if (!isset($name_perso))
    {
        $name_perso = $_SESSION['selected_perso'];
    }  

    $req = $pdo->prepare("SELECT * FROM persos WHERE name_perso = ? AND owner_perso = ?");
    $req->execute([$name_perso, $login]);
    $data_perso = $req->fetch();


    $pts_nin = $data_perso['nin_perso'] * 1.1;
    $pts_tai = $data_perso['tai_perso'] * 1.1;
    $pts_gen = $data_perso['gen_perso'] * 1.1;
    $pts_life = $data_perso['life_perso'] * 0.01;
    $power = $pts_nin + $pts_tai + $pts_gen + $pts_life;
    $power = $power + 10;
    return $power;
   
}



function current_perso_stats()
{
    global $current_perso_data, $pdo, $login, $power, $perso, $selected_perso, $name_perso;
    check_session();
    $name_perso = $_SESSION['selected_perso'];
    
    power($name_perso);
    
    $req = $pdo->prepare("
    SELECT users.*, persos.*
    FROM users
    INNER JOIN persos ON users.login = persos.owner_perso
    WHERE users.login = ?
    ");
    $req->execute(array($login));
    $perso = $req->fetch();
    $perso = $perso['selected_perso'];

    perso_stats();
}

function list_perso_stats()
{
    global $pdo, $login, $perso, $persos;
    check_session();
    $req=$pdo->prepare("SELECT * FROM persos WHERE owner_perso=?");
    $req->execute([($login)]);
    $persos=$req->fetchAll();

}


function perso_stats()
{
    global $login, $pdo, $data_mh_step, $power, $data_list_persos, $perso, $data_perso;
    check_session();
    $req = $pdo->prepare("SELECT * FROM persos WHERE name_perso = ? AND owner_perso = ?");
    $req->execute([$perso, $login]);
    $data_perso = $req->fetch();
    // echo '<br /><br /><br /><br /><b>' . $data_perso['name_perso'] . '</b>';    
    // echo '<br />Puissance : ' . power($data_perso);  
    // echo '<br />Ninjutsu : ' . $data_perso['nin_perso'];
    // echo '<br />Taijutsu : ' . $data_perso['tai_perso'];
    // echo '<br />Genjutsu : ' . $data_perso['gen_perso'];
    // echo '<br />Vie : ' . $data_perso['life_perso'];
    // echo '<br /><br />';
}


function current_mh_perso_stats()
{
    global $current_perso_data, $pdo, $login, $power, $data_mh_step, $name_perso;
    check_session();
    power($name_perso);
    mh_step();


    echo '' . $data_mh_step['mh_perso'];    
    echo '<br />Puissance : ' . $power;  
    
}

function enemy_mh_stats()
{
    global $enemy, $pdo, $login;
    check_session();
    
    $req = $pdo->prepare("
    SELECT users.*, mh_steps.*
    FROM users
    INNER JOIN mh_steps ON users.user_mh_step = mh_steps.mh_step
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
    global $pdo, $login, $power, $current_perso_mh_data, $enemy, $name_perso;
    check_session();
    mh_step();
    power($name_perso);
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
    check_session();
    mh_step();
    $reward1 = $data_mh_step['mh_reward_1'];
    echo 'Vous avez obtenu le personnage ' . $reward1;
    new_perso();
    $req = $pdo->prepare("UPDATE users SET user_mh_step = user_mh_step + 1 WHERE login = ?");
    $req->execute([($login)]);
}



?>
