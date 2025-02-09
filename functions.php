<?php
function check_login()
{
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
    echo '<br /> <br />';
	echo '<br /><a href="membre.php">Accueil</a>';
	echo '<br /><a href="perso.php">Persos</a>';
	echo '<br /><a href="histoire.php">Mode Histoire</a>';
	echo '<br /><a href="tchat.php">Tchat</a>';
	echo '<br /><a href="deconnexion.php">Se déconnecter</a>';
}
	





function mh_ini()
{
    // global $pdo, $login;
    // $req = $pdo->prepare('
    //     UPDATE users
    //     SET mh_step = :mh_step
    //     WHERE login = :login
    // ');
    // $req->execute([
    //     'mh_step' => 1,
    //     'login' => $login
    // ]);
    // echo '<br />Vous venez de débloquer de Mode Histoire !<br />';
}



function check_mh_step()
{
    // global $pdo, $login, $target_perso, $mh_step; 
    // $req = $pdo->prepare("SELECT * FROM users WHERE login=?");
    // $req->execute(array($login));
    // $donnees = $req->fetch();
    // $mh_step = $donnees['mh_step'];
    // switch ($mh_step)
    // {
    //     case 1:
    //         $target_perso = "Naruto";
    //         break;
    //     case 2:
    //         $target_perso = "Sakura";
    //         break;
    //     case 3:
    //         $target_perso = "Sasuke";
    //         break;
    //     case 5:
    //         $target_perso = "Kakashi";
    //         break;
    //     default:
    //         $target_perso = "Naruto";
    //         break;
        
    //  }
}

function get_mh_step()
{
    global $data_step, $pdo, $login;
    bdd_connexion();
    $req = $pdo->prepare("SELECT * FROM users WHERE login=?");
    $req->execute(array($login));
    $mh_data = $req->fetch();
    $data_step = $mh_data['mh_step'];

}

function get_data_current_step()
{
    global $data_mh, $pdo, $login, $data_step;
    bdd_connexion();
    get_mh_step();
    $req = $pdo->prepare("SELECT * FROM mh_steps WHERE mh_step=?");
    $req->execute(array($data_step));
    $data_mh = $req->fetch();
}

function get_new_perso()
{
    global $new_perso, $data_mh;
    get_data_current_step();
    if (!$data_mh['mh_reward_1'] == 0 && (!$data_mh['mh_reward_1'] == 'Naruto'))
    {
        $new_perso = $data_mh['mh_reward_1'];
    }
    else
    {
        $new_perso = "Sakura";
    }
}


function new_perso()
{
    global $pdo, $login, $new_perso;
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