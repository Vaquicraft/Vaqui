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
    global $pdo, $perso, $name_perso, $login, $dataPerso, $dataUser, $datareq;
    getUserData();
    require('base.php');

    $req=$pdo->prepare("SELECT * FROM persos WHERE owner_perso = ? AND name_perso = ?");
    $req->execute([$login, $dataUser['selected_perso']]);
    $dataPerso=$req->fetch();
    persoBuilderCurrentPerso($dataPerso);
    
    
}
	

function new_perso()
{
    global $pdo, $login, $dataUser;
    getUserData();
  
    $req = $pdo->prepare
    ('
        INSERT INTO persos (owner_perso, name_perso, power_perso, xp_perso, nin_perso, tai_perso, gen_perso, life_perso, avatar_perso, win, lose, draw, kills, deaths, training_nin, training_tai, training_gen, training_life)
        VALUES (:owner_perso, :name_perso, :power_perso, :xp_perso, :nin_perso, :tai_perso, :gen_perso, :life_perso, :avatar_perso, :win, :lose, :draw, :kills, :deaths, :training_nin, :training_tai, :training_gen, :training_life)
    ');

    $req->execute
    ([
        'owner_perso' => $login,
        'name_perso' => $dataUser['mh_reward_1'],
        'power_perso' => 10,
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




function updatePower($idPerso)
{
    global $pdo;
    getUserData();

    $req = $pdo->prepare("SELECT * FROM persos WHERE id_perso = ?");
    $req->execute([$idPerso]);
    $dataPerso = $req->fetch();    

    $pts_nin = $dataPerso['nin_perso'] * 1.1;
    $pts_tai = $dataPerso['tai_perso'] * 1.1;
    $pts_gen = $dataPerso['gen_perso'] * 1.1;
    $pts_life = $dataPerso['life_perso'] * 0.01;
    $power = $pts_nin + $pts_tai + $pts_gen + $pts_life;
    $power = $power + 10;

    $req = $pdo->prepare("UPDATE persos SET power_perso = ? WHERE id_perso = ?");
    $req->execute([$power, $idPerso]);
}


function persoBuilderCurrentPerso($dataPerso)
{
    global $pdo, $login, $persos, $perso, $name_perso, $dataUser, $dataPerso, $idPerso;
    getUserData();
    ?>

    <div class="persoBuilderSlide">

        <div class="persoBuilderSlideNamePerso">
            <a href="perso.php?perso=<?= $dataPerso['name_perso']; ?>">
                <?= $dataPerso['name_perso']; ?>
            </a>
        </div>
        
<?php
persoBuilderGetStats();
?>
    </div>
<?php
}

function persoBuilderOther($dataPerso)
{
    global $pdo, $login, $persos, $perso, $name_perso, $dataUser, $dataPerso;
    getUserData();
    $idPerso = $dataPerso['id_perso'];
    ?>

    <div class="persoBuilderSlide">

        <div class="persoBuilderSlideNamePerso">
            <?php
            echo $dataPerso['name_perso'];
            ?>
        </div>
        
<?php
persoBuilderGetStats();
?>
</div>
<?php
}

function persoBuilderGetStats()
{
    global $pdo, $dataPerso, $statName, $statValue, $stats;
    getUserData();

    $stats = 
    [
        "Puissance" => $dataPerso['power_perso'],
        "Ninjutsu"  => $dataPerso['nin_perso'],
        "Taijutsu"  => $dataPerso['tai_perso'],
        "Genjutsu"  => $dataPerso['gen_perso'],
        "Vie"       => $dataPerso['life_perso']
    ];

    foreach ($stats as $statName => $statValue)
    {
        ?>

        <div class="persoBuilderSlideStatsPerso">

            <div class="persoBuilderSlideStatsPersoStatName">
                <?php
                echo $statName;
                ?>
            </div>

            <div class="persoBuilderSlideStatsPersoStatValue">
                <?php
                    echo $statValue;
                ?>
            </div>
            
        </div>
        
        <?php
    }
}

function increase_stats()
{
    global $pdo, $login, $nin, $tai, $gen, $dataUser;
    getUserData();
    
    $ninjutsu = $dataUser['nin_perso'] + $nin;
    $taijutsu = $dataUser['tai_perso'] + $tai;
    $genjutsu = $dataUser['gen_perso'] + $gen;
    
    echo 'Votre entraînement a porté ses fruits !';
    ##todo réparer le echo kimarchpa

    $req = $pdo->prepare("UPDATE persos SET nin_perso = ?, tai_perso = ?, gen_perso = ? WHERE name_perso = ? AND owner_perso = ?");
    $req->execute([$ninjutsu, $taijutsu, $genjutsu, $dataUser['name_perso'], $login]);    

}


function checkMhStep()
{
    global $login, $dataUser;
    getUserData();
    if ($dataUser['mh_perso'] !== $dataUser['selected_perso'])
{



    echo 'Vous devez utiliser <a href="perso.php?perso=' . $dataUser['mh_perso'] . '">' . $dataUser['mh_perso'] . '</a> pour cette étape.'; 
    die;
}
}


function fightBuilderPage()
{
    getUserData();
}   


function levelPerso($xp) 
{
    $level = 1;
    $xpRequired = 50;

    while ($xp >= $xpRequired) {
        $xp -= $xpRequired; 
        $level++; 
        $xpRequired = 200 + (160 * ($level - 1)) + (5 * pow(($level - 1), 2));

    }
    return $level;
}

function fightProcess($fightResult)
{
    global $pdo, $fightResult, $dataFighter, $dataAdversary;
    bdd_connexion();
    
    $fighterWin = 0;
    $fighterLose = 0;
    $fighterDraw = 0;
    $fighterKills = 0;
    $fighterDeaths = 0;

    $adversaryWin = 0;
    $adversaryLose = 0;
    $adversaryDraw = 0;
    $adversaryKills = 0;
    $adversaryDeaths = 0;

    $dataFighterWin = $dataFighter['win'];
    $dataFighterLose = $dataFighter['lose'];
    $dataFighterDraw = $dataFighter['draw'];
    $dataFighterKills = $dataFighter['kills'];
    $dataFighterDeaths = $dataFighter['deaths'];
    $dataFighterXP = $dataFighter['xp_perso'];

    $dataAdversaryWin = $dataAdversary['win'];
    $dataAdversaryLose = $dataAdversary['lose'];
    $dataAdversaryDraw = $dataAdversary['draw'];
    $dataAdversaryKills = $dataAdversary['kills'];
    $dataAdversaryDeaths = $dataAdversary['deaths'];
    $dataAdversaryXP = $dataAdversary['xp_perso'];


    switch ($fightResult)
    {
        case "fighterWin":
            $xp = $dataFighter['xp_perso'];
            $xpFighter = xpCalc($xp);
            $xp = $dataAdversary['xp_perso'];
            $xpAdversary = xpCalc($xp) * 0.25;
            $fighterWin = 1;
            $adversaryLose = 1;
      
            break;
        case "adversaryWin":
            $xp = $dataFighter['xp_perso'];
            $xpFighter = xpCalc($xp) * 0.25;
            $xp = $dataAdversary['xp_perso'];
            $xpAdversary = xpCalc($xp);
            $adversaryWin = 1;
            $fighterLose = 1;
            break;
        case "doubleKill":
            $xp = $dataFighter['xp_perso'];
            $xpFighter = xpCalc($xp) * 0.25;
            $xp = $dataAdversary['xp_perso'];
            $xpAdversary = xpCalc($xp) * 0.25;
            $fighterLose = 1;
            $fighterKills = 1;
            $fighterDeaths = 1;
            $adversaryLose = 1;
            $adversaryKills = 1;
            $adversaryDeaths = 1;
            
            break;
        case "fighterKilled":
            $xp = $dataFighter['xp_perso'];
            $xpFighter = xpCalc($xp) * 0.2;
            $xp = $dataAdversary['xp_perso'];
            $xpAdversary = xpCalc($xp) * 1.15;
            $adversaryKills = 1;
            $adversaryWin = 1;
            $fighterLose = 1;
            $fighterDeaths = 1;
            break;
        case "adversaryKilled":
            $xp = $dataFighter['xp_perso'];
            $xpFighter = xpCalc($xp) * 1.15;
            $xp = $dataAdversary['xp_perso'];
            $xpAdversary = xpCalc($xp) * 0.2;
            $fighterWin = 1;
            $fighterKills = 1;
            $adversaryLose = 1;
            $adversaryDeaths = 1;
            break;
        case "draw":
            $xp = $dataFighter['xp_perso'];
            $xpFighter = xpCalc($xp) * 0.3;
            $xp = $dataAdversary['xp_perso'];
            $xpAdversary = xpCalc($xp) * 0.3;
            $fighterDraw = 1;
            $adversaryDraw = 1;
            break;
    
    }

    $xpFighter = $dataFighterXP + $xpFighter;
    $fighterWin = $dataFighterWin + $fighterWin;
    $fighterLose = $dataFighterLose + $fighterLose;
    $fighterDraw = $dataFighterDraw + $fighterDraw;
    $fighterKills = $dataFighterKills + $fighterKills;
    $fighterDeaths = $dataFighterDeaths + $fighterDeaths;

    $xpAdversary = $dataAdversaryXP + $xpAdversary;
    $adversaryWin = $dataAdversaryWin + $adversaryWin;
    $adversaryLose = $dataAdversaryLose + $adversaryLose;
    $adversaryDraw = $dataAdversaryDraw + $adversaryDraw;
    $adversaryKills = $dataAdversaryKills + $adversaryKills;
    $adversaryDeaths = $dataAdversaryDeaths + $adversaryDeaths;

    $req = $pdo->prepare("UPDATE persos SET xp_perso = ?, win = ?, lose = ?, draw = ?, kills = ?, deaths = ? WHERE id_perso = ?");
    $req->execute([$xpFighter, $fighterWin, $fighterLose, $fighterDraw, $fighterKills, $fighterDeaths, $dataFighter['id_perso']]); 
    $req->execute([$xpAdversary, $adversaryWin, $adversaryLose, $adversaryDraw, $adversaryKills, $adversaryDeaths, $dataAdversary['id_perso']]);

    $req = $pdo->prepare('
    INSERT INTO combat (fighter_id, adversary_id) 
    VALUES (:fighter_id, :adversary_id)
');
$req->execute([
    ':fighter_id'   => $dataFighter['id_perso'], 
    ':adversary_id' => $dataAdversary['id_perso']
]);


    

}


function xpCalc($xp) 
{
    global $level, $xp;
    $level = levelPerso($xp);
    if ($level <= 0) {
        return 0; 
    }
    
    return pow(1, ($level - 1)) + (1 * (log($level) * 1.75) + 19 );
}


function fightList()
{
    echo '<h2>Liste des Combats </h2>';
    ?>
    <div class="fightBuilderSlide">
        <div class="fightBuilderSlideLogin">
            <p>Pseudo</p>
        </div>
        
        <div class="fightBuilderSlideName">
            <p>Personnage</p>
        </div>

        <div class="fightBuilderSlideLevel">
            <p>Niveau</p>
        </div>

        <div class="fightBuilderSlideLevel">
            <p>Puissance</p>
        </div>

        <div class="fightBuilderSlideWin">
            <p>G</p>
        </div>

        <div class="fightBuilderSlideLose">
            <p>P</p>
        </div>

        <div class="fightBuilderSlideKill">
            <p>T</p>
        </div>

        <div class="fightBuilderSlideDraw">
            <p>N</p>
        </div>

        <div class="fightBuilderSlideFightButton">
            <p>Combattre</p>
        </div>
    </div>
    <?php
}

function fightListBuilder()
{
    global $pdo, $fighter, $xp, $fightList;

    ?>
    
    <div class="fightContent">
            <div class="fightContentSlideLogin">
                <?php
                echo $fighter['owner_perso'];
                ?>
            </div>
            
            <div class="fightContentSlideName">
            <?php
                echo $fighter['name_perso'];
                ?>
            </div>

            <div class="fightContentSlideLevel">
            <?php
                $xp = $fighter['xp_perso'];
                echo levelPerso($xp);
                ?>
            </div>

            <div class="fightContentSlidePower">
            <?php
                $idPerso = $fighter['id_perso'];
                echo $fighter['power_perso']
                ?>
            </div>

            <div class="fightContentSlideWin">
            <?php
                echo $fighter['win'];
                ?>
            </div>

            <div class="fightContentSlideLose">
            <?php
                echo $fighter['lose'];
                ?>
            </div>

            <div class="fightContentSlideKill">
            <?php
                echo $fighter['kills'];
                ?>
            </div>

            <div class="fightContentSlideDraw">
            <?php
                echo $fighter['draw'];
                ?>
            </div>
            <div class="fightContentSlideFightButton">
            <?php
           echo '<a href="fightprocess.php?id=' . $fighter['id_perso'] . '"><img src="images/fight.png" alt=""></a>'; 
            ?>
            
            </div>
    </div>
<?php
}

?>