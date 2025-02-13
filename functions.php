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
        $selected_perso = $data['selected_perso'];
        $_SESSION['selected_perso'] = $selected_perso;
    }
}


function getUserData()
{
    global $login, $pdo, $dataUser, $data_perso, $selected_perso;
    check_session();
    $req = $pdo->prepare("
    SELECT users.*, persos.*, mh_steps.*
    FROM users
    INNER JOIN persos ON users.selected_perso = persos.name_perso
    INNER JOIN mh_steps ON users.user_mh_step = mh_steps.mh_step
    WHERE users.login = ?
    AND users.selected_perso = ?
    ");
    $req->execute([$login, $selected_perso]);
    $dataUser = $req->fetch();
}


function menu()
{
    global $pdo, $perso, $name_perso, $login;
    check_session();
    getUserData();
    include('base.html');
    persoBuilder();   
}
	

function new_perso()
{
    global $pdo, $login, $dataUser;
    getUserData();
  
    $req = $pdo->prepare
    ('
        INSERT INTO persos (owner_perso, name_perso, xp_perso, nin_perso, tai_perso, gen_perso, life_perso, avatar_perso, win, lose, draw, kills, deaths, training_nin, training_tai, training_gen, training_life)
        VALUES (:owner_perso, :name_perso, :xp_perso, :nin_perso, :tai_perso, :gen_perso, :life_perso, :avatar_perso, :win, :lose, :draw, :kills, :deaths, :training_nin, :training_tai, :training_gen, :training_life)
    ');

    $req->execute
    ([
        'owner_perso' => $login,
        'name_perso' => $dataUser['mh_reward_1'],
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
    global $pdo, $login, $power, $data, $perso, $persoName, $dataUser;
    getUserData();
    if (!isset($name_perso))
    {
        $name_perso = $_SESSION['selected_perso'];
    }  
    $pts_nin = $dataUser['nin_perso'] * 1.1;
    $pts_tai = $dataUser['tai_perso'] * 1.1;
    $pts_gen = $dataUser['gen_perso'] * 1.1;
    $pts_life = $dataUser['life_perso'] * 0.01;
    $power = $pts_nin + $pts_tai + $pts_gen + $pts_life;
    $power = $power + 10;
    return $power;
}

function persoBuilder()
{
    global $pdo, $login, $persos, $perso, $name_perso, $dataUser;
    getUserData();
    $name_perso = $dataUser['name_perso']; 
    ?>

    <div class="persoBuilderSlide">

        <div class="persoBuilderSlideNamePerso">
        <?php
        persoStatsBuilderGetName();
        ?>
        </div>

        <div class="persoBuilderSlideStatsPerso">
            <div class="persoBuilderSlideStatsPersoStatName">
                Puissance :
            </div>
            <div class="persoBuilderSlideStatsPersoStatValue">
            <?php
                persoStatsBuilderGetPower();
            ?>
            </div>
        </div>

        <div class="persoBuilderSlideStatsPerso">
            <div class="persoBuilderSlideStatsPersoStatName">
                Ninjutsu :
            </div>
            <div class="persoBuilderSlideStatsPersoStatValue">
            <?php
                persoStatsBuilderGetNinjutsu();
            ?>
            </div>
        </div>

        <div class="persoBuilderSlideStatsPerso">
            <div class="persoBuilderSlideStatsPersoStatName">
                Taijutsu :
            </div>
            <div class="persoBuilderSlideStatsPersoStatValue">
            <?php
                persoStatsBuilderGetTaijutsu();
            ?>
            </div>
        </div>

        <div class="persoBuilderSlideStatsPerso">
            <div class="persoBuilderSlideStatsPersoStatName">
                Genjutsu :
            </div>
            <div class="persoBuilderSlideStatsPersoStatValue">
            <?php
                persoStatsBuilderGetTaijutsu();
            ?>
            </div>
        </div>

        <div class="persoBuilderSlideStatsPerso">
            <div class="persoBuilderSlideStatsPersoStatName">
                Vie :
            </div>
            <div class="persoBuilderSlideStatsPersoStatValue">
            <?php
                persoStatsBuilderGetLife();
            ?>
            </div>
        </div>
    </div>


<?php
}



function persoStatsBuilderGetName()
{
    global $perso, $dataUser;
    echo '<a href="perso.php?perso=' . $dataUser['name_perso'] . '">' . $dataUser['name_perso'] . '</br></a>'; 
}

function persoStatsBuilderGetPower()
{
    global $dataUser;
    getUserData();
    echo power($dataUser['name_perso']);
}

function persoStatsBuilderGetNinjutsu()
{
    global $dataUser;
    getUserData();
    echo $dataUser['nin_perso'];
}

function persoStatsBuilderGetTaijutsu()
{
    global $dataUser;
    getUserData();
    echo $dataUser['tai_perso'];
}

function persoStatsBuilderGetGenjutsu()
{
    global $dataUser;
    getUserData();
    echo $dataUser['gen_perso'];
}

function persoStatsBuilderGetLife()
{
    global $dataUser;
    getUserData();
    echo $dataUser['life_perso'];
}



function increase_stats()
{
    global $pdo, $login, $nin, $tai, $gen, $dataUser;
    getUserData();
    
    $ninjutsu = $dataUser['nin_perso'] + $nin;
    $taijutsu = $dataUser['tai_perso'] + $tai;
    $genjutsu = $dataUser['gen_perso'] + $gen;
    
    echo 'Votre entraînement a porté ses fruits !';
    $req = $pdo->prepare("UPDATE persos SET nin_perso = ?, tai_perso = ?, gen_perso = ? WHERE name_perso = ? AND owner_perso = ?");
    $success = $req->execute([$ninjutsu, $taijutsu, $genjutsu, $dataUser['name_perso'], $login]);    

}

?>