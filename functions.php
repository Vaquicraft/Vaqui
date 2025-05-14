<link rel="stylesheet" href="./newstyle.css">

<?php

// Vérifie si l'utilisateur possède une session PHP,si ce n'est pas le cas on initialise la session avec session_start(). 
// On retourne éventuellement la redirection nécessaire en fonction du cas
// Cette fonction doit être appelée à chaque fois que l'on doit manipuler les variables $_SESSION.

function initSession(): void
{
    global $redirect;
    
    if (session_status() == PHP_SESSION_NONE)
    {
        session_start();
    }

    if (basename($_SERVER['PHP_SELF']) == "inscription.php")
        {
            $redirect = "inscription.php";
            return;
        }

    if (empty($_SESSION['login']))
    {
        
        $redirect = "index.php";
    }
    else
    {
        // $redirect = "membre.php";
        return;
    }
    if (basename($_SERVER['PHP_SELF']) != $redirect)
    {
        header("location:$redirect");
    }
}

//Connexion à la BDD principale
function bddConnexion(): void
{
    global $pdo; //On doit récupérer le PDO quand on appelle la fonction
    try
    {
        $pdo = new PDO('mysql:host=localhost;dbname=db;charset=utf8', 'root', '');
    }

    catch (PDOException $e)
    {
        error_log('Erreur de connexion à la BDD Principale' . $e->getMessage());
        exit('Une erreur technique est survenue.');
    }
}

//Appelle la fonction qui initialise les sessions utilisateur
//Appelle la fonction qui initialise la base de données principale
function initGlobal(): void
{
    initSession();
    bddConnexion();
}



function getUserData()
{
    global $login, $selectedPerso, $pdo, $globalDataUser, $globalDataPerso, $globalDataStory;
    initGlobal();

    $login = $_SESSION['login'];
    $selectedPerso = $_SESSION['selectedPerso'];
    
    // Liaison tables users et persos -> login
    $req=$pdo->prepare("
    SELECT persos.*, users.*
    FROM persos 
    INNER JOIN users ON persos.owner_perso = users.login
    WHERE persos.owner_perso = ?
    ");
    $req->execute([$login]);
    $globalDataUser=$req->fetch();

    // Liaison tables users et persos -> name_perso
    $req = $pdo->prepare("
    SELECT persos.*, users.*
    FROM users 
    INNER JOIN persos ON persos.name_perso = users.selected_perso
    WHERE selected_perso = ?
    AND owner_perso = ?");
    $req->execute([$globalDataUser['selected_perso'], $login]);
    $globalDataPerso = $req->fetch();

    
    // Liaison tables users et mh_steps -> mh_step
    $req=$pdo->prepare("
    SELECT users.*, mh_steps.*
    FROM users 
    INNER JOIN mh_steps ON mh_steps.mh_step = users.user_mh_step
    WHERE users.user_mh_step = ?
    ");
    $req->execute([$globalDataUser['user_mh_step']]);
    $globalDataStory=$req->fetch();
}

function newPerso($namePerso)
{
    global $pdo, $login, $globalDataUser, $globalDataStory, $name;
    initGlobal();

    $login = $_SESSION['login'];
  
    $req = $pdo->prepare
    ('
        INSERT INTO persos (owner_perso, name_perso, power_perso, xp_perso, nin_perso, tai_perso, gen_perso, life_perso, avatar_perso, win, lose, draw, kills, deaths, training_nin, training_tai, training_gen, training_life)
        VALUES (:owner_perso, :name_perso, :power_perso, :xp_perso, :nin_perso, :tai_perso, :gen_perso, :life_perso, :avatar_perso, :win, :lose, :draw, :kills, :deaths, :training_nin, :training_tai, :training_gen, :training_life)
    ');

    $req->execute
    ([
        'owner_perso' => $login,
        'name_perso' => $namePerso,
        'power_perso' => 10,
        'xp_perso' => 0,
        'nin_perso' => 1,
        'tai_perso' => 1,
        'gen_perso' => 1,
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

function calcXPLevel($xp)
{
    global $pdo, $globalDataPerso;
    getUserData();
    
    $level = 1;
    $xpRequired = 200;
    $currentXP = 0;
    $totalXpRequired = $xpRequired;
    
    while ($xp >= $totalXpRequired) {
        $level++;
        $xpRequired = 200 + (160 * ($level - 1)) + (5 * pow(($level - 1), 2));
        $totalXpRequired += $xpRequired;
    }
    
    return [
        'level' => $level,
        'xpActuel' => $xp - ($totalXpRequired - $xpRequired),
        'xpRequis' => $xpRequired,
        'baseXP' => pow(1, ($level - 1)) + (1 * (log($level) * 1.75) + 19 ),
    ];
}


function persoBuilderGlobal($dataPerso, $persoBuilderDisplayValue, $persoBuilderTarget)
{
    global $pdo, $login, $globalDataStory, $globalDataUser, $dataPerso, $persoBuilderDisplayValue, $avatar, $persoBuilderTarget;
    getUserData();

    updatePower($dataPerso);
    $avatar = $dataPerso['name_perso'] . $dataPerso['avatar_perso'];


    if ($persoBuilderTarget == "player")
    {
        $xp = $dataPerso['xp_perso'];
        $dataXP = calcXPLevel($xp);
        $displayRemainingXPPercentage = round(($dataXP['xpActuel'] / $dataXP['xpRequis']) * 100,2);
        $dataBuilderName = $dataPerso['name_perso'];
        $dataBuilderPower = $dataPerso['power_perso'];
        $stats = 
        [
            "Niveau" => $dataXP['level'] . ' (' .$displayRemainingXPPercentage . '%)' ,
            "Ninjutsu"  => $dataPerso['nin_perso'],
            "Taijutsu"  => $dataPerso['tai_perso'],
            "Genjutsu"  => $dataPerso['gen_perso'],
            "Vie"  => $dataPerso['life_perso'],
        ];
    }

    if ($persoBuilderTarget == "fight")
    {
        $xp = $dataPerso['xp_perso'];
        $dataXP = calcXPLevel($xp);
        $dataBuilderName = $dataPerso['name_perso'];
        $dataBuilderPower = $dataPerso['power_perso'];
        $stats = 
        [
            "Niveau" => $dataXP['level'],
            "Vie"  => $dataPerso['life_perso'],
        ];
    }

    if ($persoBuilderTarget == "story")
    {
        $dataBuilderName = $globalDataStory['mh_enemy'];
        $dataBuilderPower = $globalDataStory['mh_power'];
        $avatar = $globalDataStory['mh_enemy'] . '1';
        $statValue = "?";
        $stats =  [];
    }

    

    ?>
    <div class="persoBuilder">
        <div class="persoBuilderName">
            
            <?php
            if ($persoBuilderDisplayValue == "noLink")
            {
                echo $dataBuilderName . ' (' . $dataBuilderPower . ')';
            }
            if ($persoBuilderDisplayValue == "link")
            {
                ?>
                <a href="perso.php?perso=<?= $dataPerso['name_perso']; ?>">
                    <?= $dataPerso['name_perso'] . ' (' . $dataPerso['power_perso'] . ')'; ?>
                </a>
                <?php
            }
            
            ?>
        </div>

        <div class="persoBuilderMain">
            <div class="persoBuilderMainStat">
                <?php


        
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
                <img class="persoAvatar" src="images/avatars/<?= $avatar ?>.jpeg" alt"">
            </div>     
        </div>
    </div>
    <?php
    
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
    global $pdo, $login, $globalDataStory, $globalDataUser, $globalDataPerso, $totalMHStep;
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


    if ($globalDataStory['mh_perso'] !== $globalDataUser['selected_perso'])
{
    echo 'Vous devez utiliser <a href="perso.php?perso=' . $globalDataStory['mh_perso'] . '">' . $globalDataStory['mh_perso'] . '</a> pour cette étape.'; 
    die;
}

}

function fightProcess($fightResult)
{
    global $pdo, $fightResult, $dataFighter, $dataAdversary, $dataXP;
    bddConnexion();

        
    $fighterWin = 0;
    $fighterLose = 0;
    $fighterDraw = 0;
    $fighterKills = 0;
    $fighterDeaths = 0;
    $xp = $dataFighter['xp_perso'];
    $fighterBaseXP = calcXPLevel($xp);
    $fighterBaseXP = $dataXP['baseXP'];

    $adversaryWin = 0;
    $adversaryLose = 0;
    $adversaryDraw = 0;
    $adversaryKills = 0;
    $adversaryDeaths = 0;
    $xp = $dataAdversary['xp_perso'];
    $aversaryBaseXP = calcXPLevel($xp);
    $aversaryBaseXP = $dataXP['baseXP'];

    $xpFighter = 0;
    $xpAdversary = 0;
    

    switch ($fightResult)
    {
        case "fighterWin":
            $xpFighter = $fighterBaseXP;
            $xpAdversary = $aversaryBaseXP * 0.25;
            $fighterWin = 1;
            $adversaryLose = 1;
            break;

        case "adversaryWin":
            $xpFighter = $fighterBaseXP * 0.25;
            $xpAdversary = $aversaryBaseXP;
            $adversaryWin = 1;
            $fighterLose = 1;
            break;

        case "doubleKill":
            $xpAdversary = $aversaryBaseXP * 0.25;
            $xpFighter = $fighterBaseXP * 0.25;
            $fighterLose = 1;
            $fighterKills = 1;
            $fighterDeaths = 1;
            $adversaryLose = 1;
            $adversaryKills = 1;
            $adversaryDeaths = 1; 
            break;

        case "fighterKilled":
            $xpFighter = $fighterBaseXP * 0.2;
            $xpAdversary = $aversaryBaseXP * 1.15;
            $adversaryKills = 1;
            $adversaryWin = 1;
            $fighterLose = 1;
            $fighterDeaths = 1;
            break;

        case "adversaryKilled":
            $xpFighter = $fighterBaseXP * 1.15;
            $xpAdversary = $aversaryBaseXP * 0.2;
            $fighterWin = 1;
            $fighterKills = 1;
            $adversaryLose = 1;
            $adversaryDeaths = 1;
            break;

        case "draw":
            $xpFighter = $fighterBaseXP * 0.3;
            $xpAdversary = $aversaryBaseXP * 0.3;
            $fighterDraw = 1;
            $adversaryDraw = 1;
            break;
    
    }
    echo 'Vous avez gagné ' . $xpFighter . ' xp';

    $req = $pdo->prepare("
    UPDATE persos SET xp_perso = ?, win = ?, lose = ?, draw = ?, kills = ?, deaths = ? WHERE id_perso = ?
    ");

    $req->execute([
        $dataFighter['xp_perso'] + round($xpFighter,2),     
        $dataFighter['win'] + $fighterWin,         
        $dataFighter['lose'] + $fighterLose,       
        $dataFighter['draw'] + $fighterDraw,       
        $dataFighter['kills'] + $fighterKills,     
        $dataFighter['deaths'] + $fighterDeaths,   
        $dataFighter['id_perso']                   
    ]);

    $req->execute([
        $dataAdversary['xp_perso'] + round($xpAdversary,2),     
        $dataAdversary['win'] + $adversaryWin,         
        $dataAdversary['lose'] + $adversaryLose,       
        $dataAdversary['draw'] + $adversaryDraw,       
        $dataAdversary['kills'] + $adversaryKills,     
        $dataAdversary['deaths'] + $adversaryDeaths,   
        $dataAdversary['id_perso']                    
    ]);

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
    global $pdo, $adversary, $xp, $fightList, $adversaryLevel;
    ?>
    
    <div class="fightContent">
            <div class="fightContentSlideLogin">
                <?php
                echo $adversary['owner_perso'];
                ?>
            </div>
            
            <div class="fightContentSlideName">
            <?php
                echo $adversary['name_perso'];
                ?>
            </div>

            <div class="fightContentSlideLevel">
            <?php
                $xp = $adversary['xp_perso'];
                $dataXP = calcXPLevel($xp);
                $adversaryLevel = $dataXP['level'];
                echo $adversaryLevel;
                ?>
            </div>

            <div class="fightContentSlidePower">
            <?php
                $idPerso = $adversary['id_perso'];
                echo $adversary['power_perso']
                ?>
            </div>

            <div class="fightContentSlideWin">
            <?php
                echo $adversary['win'];
                ?>
            </div>

            <div class="fightContentSlideLose">
            <?php
                echo $adversary['lose'];
                ?>
            </div>

            <div class="fightContentSlideKill">
            <?php
                echo $adversary['kills'];
                ?>
            </div>

            <div class="fightContentSlideDraw">
            <?php
                echo $adversary['draw'];
                ?>
            </div>
            <div class="fightContentSlideFightButton">
            <?php
           echo '<a href="fightprocess.php?id=' . $adversary['id_perso'] . '"><img src="images/fight.png" alt=""></a>'; 
            ?>
            
            </div>
    </div>
<?php
}

function getFights($displayFightList)
{
    global $fightCount, $pdo, $login, $globalDataUser, $globalDataPerso , $adversary, $skip, $skipLevel, $levelPerso, $levelFighter, $fightList, $req, $dataCombat, $xp, $dataXP, $idPerso, $adversaryLevel;
    getUserData();
    $req = 'SELECT * FROM persos ORDER BY xp_perso DESC';
    $req = $pdo->prepare($req);
    $req->execute();
    $fightList = $req->fetchAll();

    $req = 'SELECT * FROM combat WHERE fighter_id = ?';
    $req = $pdo->prepare($req);
    $req->execute([$globalDataPerso['id_perso']]);
    $dataCombat = $req->fetchAll();

    if ($displayFightList == true)
    {
        fightList();
    }
    
    $xp = $globalDataPerso['xp_perso'];
    $dataXP = calcXPLevel($xp);
    $levelPerso = $dataXP['level'];

    $fightCount = 0;
    
    foreach ($fightList as $adversary)
    {

        if ($adversary['owner_perso'] == $login)
        {
            continue;
        }
        $skip = false;
             
        $xp = $adversary['xp_perso'];
        $dataXP = calcXPLevel($xp);
        $adversaryLevel = $dataXP['level'];
        
        if ($levelPerso + 2 < $adversaryLevel || ($levelPerso - 2 > $adversaryLevel))
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
                if ($adversary['id_perso'] == $value['adversary_id'])
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
        $idPerso = $adversary['id_perso'];
        ?>
        
        <div class="fightListContent">
            <?php
        if ($displayFightList == true)
        {
            fightListBuilder();
        }    
        ?>
        </div>
        <?php
        
    }   

    return $fightCount;
}








//Vérifie les informations de connexion depuis la page connexion.php et vers la BDD reliée à l'utilisateur concerné.
//Attribue les variables de session lors de la connexion
function connexionProcess(): void
{
    global $pdo;
    
    initGlobal();

    $login = $_POST['login'];
    $password = $_POST['pass'];

	$hache = sha1($password);			   
	$req=$pdo->prepare('SELECT * FROM users WHERE login=?');
	$req->execute([$login]);
	$data=$req->fetch();
		
	if (!$data || !password_verify($password, $data['password']))
	{
        $_SESSION['connexionMessage'] = "Identifiants incorrects.";
	}
		
	else //Connexion réussie
	{
		$_SESSION['login'] = $data['login'];
		$_SESSION['selectedPerso'] = $data['selected_perso'];
        header('location:membre.php');
	}
}


function registerProcess(): void
{
    global $pdo, $errors, $name, $registerSuccess;
    initSession();
    bddConnexion();


    $req=$pdo->prepare("SELECT * FROM users WHERE login=?");
    $req->execute(array($_POST['login']));
    $data=$req->fetch();



    $_SESSION['registerError'] = [];

    if (!empty($data))
    {
        $errors[] = "Ce nom d'utilisateur est déjà utilisé.";
    }

    if (strlen($_POST['login']) <= 3)
    {
        $errors[] = "Le nom d'utilisateur est trop court (4 caractères minimum)";
    }

    if (strlen($_POST['password']) <= 6)
    {
        $errors[] = "Le mot de passe est trop court. (7 caractères minimum)";
    }

    if ($_POST['password'] != $_POST['passwordverif'])
    {
        $errors[] = "Le mot de passe est différent de la confirmation.";
    }

    $req=$pdo->prepare("SELECT * FROM users WHERE mail=?");
    $req->execute(array($_POST['mail']));
    $data=$req->fetch();

    if (!empty($data))
    {
        $errors[] = "Cette adresse mail est déjà utilisée.";
    }

    if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['mail']))
    {
        $errors[] = "L'adresse mail est invalide.";
    }

    if(!empty($errors))
    {
        return;
    }

    //Inscription réussie
    else 
    {
    
        $pass_hache = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $login = $_POST['login'];
        $password = $_POST['password'];
        $passwordverif = $_POST['passwordverif'];
        $mail = $_POST['mail'];
        $firstPerso = "Naruto";

        $_SESSION['login'] = $login;
        $_SESSION['selectedPerso'] = $firstPerso;
                
        // Insertion des données de l'utilisateur dans la table users de la base de données principale
        $req = $pdo->prepare('INSERT INTO users(login, password, mail, date_inscription, user_mh_step, selected_perso) VALUES(:login, :password, :mail, NOW(), :user_mh_step, :selected_perso)');
        $req->execute(array(
            'login' => $login,
            'password' => $pass_hache,
            'mail' => $mail,
            'user_mh_step' => 1,
            'selected_perso' => $firstPerso
        ));

    
        newPerso($firstPerso);

        $registerSuccess = "Votre inscription s'est déroulée avec succès !" ;
        session_destroy();

        
    }
}

?>