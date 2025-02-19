<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naruto Project</title>
    <link rel="stylesheet" href="/style.css" /> 
</head>
<body>
   
</body>
</html>

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
    global $login, $pdo, $globalDataUser, $globalDataStory, $globalDataPerso;
    check_session();

    $req=$pdo->prepare("
    SELECT persos.*, users.*
    FROM persos 
    INNER JOIN users ON persos.owner_perso = users.login
    WHERE persos.owner_perso = ?
    ");
    $req->execute([$login]);
    $globalDataUser=$req->fetch();

    $req = $pdo->prepare("
    SELECT persos.*, users.*
    FROM users 
    INNER JOIN persos ON persos.name_perso = users.selected_perso
    WHERE selected_perso = ?
    AND owner_perso = ?");
    $req->execute([$globalDataUser['selected_perso'], $login]);
    $globalDataPerso = $req->fetch();

    $req=$pdo->prepare("
    SELECT users.*, mh_steps.*
    FROM users 
    INNER JOIN mh_steps ON mh_steps.mh_step = users.user_mh_step
    WHERE users.user_mh_step = ?
    ");
    $req->execute([$globalDataUser['user_mh_step']]);
    $globalDataStory=$req->fetch();
}


function menu()
{
    global $pdo;
    getUserData();
    ?>
    <nav class="links">
    <?php
    links();
    ?>
    </nav>
    <?php
}

function footer()
{
    global $pdo, $dataPerso, $persoBuilderDisplayValue, $login, $globalDataUser;
    getUserData();
    ?>
    <footer>
        <?php
        $req=$pdo->prepare("SELECT * FROM persos WHERE owner_perso = ? AND name_perso = ?");
        $req->execute([$login, $globalDataUser['selected_perso']]);
        $dataPerso=$req->fetch();
        $persoBuilderDisplayValue = "noLink";
        persoBuilder($dataPerso, $persoBuilderDisplayValue);
        ?>
    </footer>
    <?php
}


function new_perso()
{
    global $pdo, $login, $globalDataUser, $globalDataStory;
    getUserData();
  
    $req = $pdo->prepare
    ('
        INSERT INTO persos (owner_perso, name_perso, power_perso, xp_perso, nin_perso, tai_perso, gen_perso, life_perso, avatar_perso, win, lose, draw, kills, deaths, training_nin, training_tai, training_gen, training_life)
        VALUES (:owner_perso, :name_perso, :power_perso, :xp_perso, :nin_perso, :tai_perso, :gen_perso, :life_perso, :avatar_perso, :win, :lose, :draw, :kills, :deaths, :training_nin, :training_tai, :training_gen, :training_life)
    ');

    $req->execute
    ([
        'owner_perso' => $login,
        'name_perso' => $globalDataStory['mh_reward_1'],
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




function updatePower($globalDataPerso)
{
    global $pdo, $globalDataPerso;
    getUserData();
    $pts_nin = $globalDataPerso['nin_perso'] * 1.1;
    $pts_tai = $globalDataPerso['tai_perso'] * 1.1;
    $pts_gen = $globalDataPerso['gen_perso'] * 1.1;
    $pts_life = $globalDataPerso['life_perso'] * 0.01;
    $power = $pts_nin + $pts_tai + $pts_gen + $pts_life;
    $power = $power + 10;
    $req = $pdo->prepare("UPDATE persos SET power_perso = ? WHERE id_perso = ?");
    $req->execute([$power, $globalDataPerso['id_perso']]);
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



function persoBuilder($dataPerso, $persoBuilderDisplayValue)
{
    global $pdo, $login, $persos, $perso, $name_perso, $globalDataUser, $dataPerso, $idPerso, $persoBuilderDisplayValue, $avatar;
    getUserData();
    updatePower($dataPerso)
    ?>
    <div class="persoBuilder">
        <div class="persoBuilderName">
        
            <?php
            if ($persoBuilderDisplayValue == "link")
            {
                ?> 
                <a href="perso.php?perso=<?= $dataPerso['name_perso']; ?>">
                    <?= $dataPerso['name_perso']; ?>
                </a>
                <?php
            }
            else
            {
                echo $dataPerso['name_perso'];
            }
            echo ' (' . $dataPerso['power_perso'] . ')';
            ?>

        </div>

        <div class="persoBuilderMain">
            <div class="persoBuilderMainStat">
                <?php
                persoBuilderGetStats();
                ?>
            </div>

            <div class ="persoBuilderMainAvatar">
                <?php
                $selectedPerso = $dataPerso['name_perso'];
                $selectedAvatar = $dataPerso['avatar_perso'];
                $avatar = $selectedPerso . $selectedAvatar;
                displayAvatar();
                ?>
            </div>


            
        </div>
    </div>
    <?php
    
}

function persoBuilderStory()
{
    global $pdo, $login, $globalDataStory, $globalDataUser, $dataPerso, $persoBuilderDisplayValue, $avatar;
    getUserData();
    $dataPerso = $globalDataUser;
    updatePower($dataPerso)
    ?>
    <div class="persoBuilder">
        <div class="persoBuilderName">
        
            <?php
           
            echo $globalDataStory['mh_enemy'];
           
            echo ' (' . $globalDataStory['mh_power'] . ')';
            ?>

        </div>

        <div class="persoBuilderMain">
            <div class="persoBuilderMainStat">
                <?php
                
    $statValue = "?";
    $stats = 
    [
        "Niveau" => $statValue,
        "XP" => $statValue,
        "Ninjutsu"  => $statValue,
        "Taijutsu"  => $statValue,
        "Genjutsu"  => $statValue,
    ];

        
    foreach ($stats as $statName => $statValue)
    {
        ?>
        <div class="persoBuilderStats">
            <div class="persoBuilderStatsName">
                <?php
                echo $statName;
                ?>
            </div>

            <div class="persoBuilderStatsValue">
                <?php
                echo ' : ' . $statValue;
                  
                ?>
            </div>
        </div>
    <?php
    }

                ?>
            </div>

            <div class ="persoBuilderMainAvatar">
                <?php
                $selectedPerso = $dataPerso['name_perso'];
                $selectedAvatar = $dataPerso['avatar_perso'];
                $avatar = $globalDataStory['mh_enemy'] . '1';
                displayAvatar();
                ?>
            </div>


            
        </div>
    </div>
    <?php
    
}

function displayAvatar()
{
    global $dataPerso, $globalDataUser, $pdo, $avatar;
    getUserData();
    ?>
    <img class="persoAvatar" src="images/avatars/<?= $avatar ?>.jpeg" alt"">
    <?php
}



function persoBuilderGetStats()
{
    global $pdo, $dataPerso, $statName, $statValue, $stats, $xp, $level;
    getUserData();

    $xp = $dataPerso['xp_perso'];
    $stats = 
    [
        "Niveau" => levelPerso($xp),
        "XP" => $dataPerso['xp_perso'],
        "Ninjutsu"  => $dataPerso['nin_perso'],
        "Taijutsu"  => $dataPerso['tai_perso'],
        "Genjutsu"  => $dataPerso['gen_perso'],
    ];

        
    foreach ($stats as $statName => $statValue)
    {
        ?>
        <div class="persoBuilderStats">
            <div class="persoBuilderStatsName">
                <?php
                echo $statName;
                ?>
            </div>

            <div class="persoBuilderStatsValue">
                <?php
                echo ' : ' . $statValue;
                  
                ?>
            </div>
        </div>
    <?php
    }

}

function increase_stats()
{
    global $pdo, $login, $nin, $tai, $gen, $globalDataUser, $globalDataPerso;
    getUserData();
    
    $ninjutsu = $globalDataPerso['nin_perso'] + $nin;
    $taijutsu = $globalDataPerso['tai_perso'] + $tai;
    $genjutsu = $globalDataPerso['gen_perso'] + $gen;
    
    echo 'Votre entraînement a porté ses fruits !';
    ##todo réparer le echo kimarchpa

    $req = $pdo->prepare("UPDATE persos SET nin_perso = ?, tai_perso = ?, gen_perso = ? WHERE name_perso = ? AND owner_perso = ?");
    $req->execute([$ninjutsu, $taijutsu, $genjutsu, $globalDataPerso['name_perso'], $login]);    

}


function checkMhStep()
{
    global $pdo, $login, $globalDataStory, $totalMHStep;
    getUserData();
    ##SECURITÉ
    $req = "SELECT COUNT(*) AS total FROM mh_steps";
    $req=$pdo->query($req);
    $dataMH = $req->fetch();
    $totalMHStep = $dataMH['total'];

    if (!isset($globalDataStory['user_mh_step']))
    {
        echo "TRUETEST";
        $req = $pdo->prepare("UPDATE users SET user_mh_step = ? WHERE login = ?");
        $req->execute([$totalMHStep, $login]);   
    }

    if ($globalDataStory['mh_step'] == $totalMHStep)
    {
        echo "Vous avez terminé le MH (pour l'instant...) Félicitations.";
        die;
    }
    ##SECURITÉ


    if ($globalDataStory['mh_perso'] !== $globalDataStory['selected_perso'])
{
    echo 'Vous devez utiliser <a href="perso.php?perso=' . $globalDataStory['mh_perso'] . '">' . $globalDataStory['mh_perso'] . '</a> pour cette étape.'; 
    die;
}

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

function getFights($displayFightList)
{
    global $fightCount, $pdo, $login, $globalDataUser, $globalDataPerso, $fighter, $skip, $skipLevel, $levelPerso, $levelFighter, $fightList, $req, $dataCombat, $xp, $idPerso;
    getUserData();
    $req = 'SELECT * FROM persos ORDER BY xp_perso DESC';
    $req = $pdo->prepare($req);
    $req->execute();
    $fightList = $req->fetchAll();

    $req = 'SELECT * FROM combat WHERE fighter_id = ?';
    $req = $pdo->prepare($req);
    $req->execute([$globalDataUser['id_perso']]);
    $dataCombat = $req->fetchAll();

    if ($displayFightList == true)
    {
        fightList();

    }
    
    $xp = $globalDataPerso['xp_perso'];
    $levelPerso = levelPerso($xp);
    echo $globalDataPerso['xp_perso'];

    

    $fightCount = 0;
    
    foreach ($fightList as $fighter)
    {

        if ($fighter['owner_perso'] == $login)
        {
            continue;
        }
        $skip = false;
        // $skipLevel = true;
        
        
        $xp = $fighter['xp_perso'];
        $levelFighter = levelPerso($xp);
        
        if ($levelPerso + 2 < $levelFighter || ($levelPerso - 2 > $levelFighter))
        {
            $skipLevel = true;
            
            if ($skipLevel)
            {
                continue;
            }
        }
        


        
        if(!empty($dataCombat))
        {
            foreach ($dataCombat as $value)
            {
                if ($fighter['id_perso'] == $value['adversary_id'])
                {
                    $skip = true;
                    break;      
                } 
            }    
            if ($skip)
            {
                continue;
                
        
            }
            
        }
        $fightCount++;
        $idPerso = $fighter['id_perso'];
        
        if ($displayFightList == true)
        {
            fightListBuilder();
        }
        

    }
    

    return $fightCount;
}

function links()
{
    global $fightCount, $displayFightList;
    $displayFightList = false;
    getFights($displayFightList);
?>


            <ul>
                <li>
                    <img src="images/home.png" alt="home_icon">
                    <a href="membre.php">Accueil</a>
                </li>
                
                <li>
                    <img src="images/perso.png" alt="home_icon">
                    <a href="perso.php">Info Perso</a>  
                </li>

                <li>
                    <img src="images/perso.png" alt="home_icon">
                    <a href="listpersos.php">Liste des Persos</a>  
                </li>
                
                <li>
                    <img src="images/fist.png" alt="home_icon">
                    <?php
                    echo '<a href="fight.php"> Combats (<span style="color:white">' . $fightCount . '</span>) </a>';

                    ?>
               
                </li>
                <li>
                    <img src="images/mh.png" alt="home_icon">
                    <a href="story.php">Mode Histoire</a>  
                </li>
                
                <li>
                    <img src="images/training.png" alt="home_icon">
                    <a href="training.php">Entraînements</a>
                </li>
                <li>
                    <img src="images/logout.png" alt="home_icon">
                    <a href="deconnexion.php">Déconnexion</a>
                </li>
        
            </ul>


        <?php
}

?>