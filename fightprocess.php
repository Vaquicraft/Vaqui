<?php

require 'functions.php';
getUserData();
menu();

if (!isset($_GET['id'])) 
{
    header("fight.php");
    exit;
}

echo '<h2>Combat !</h2>';

$currentPerso = $_SESSION['selected_perso'];

$req = $pdo->prepare("SELECT * FROM persos WHERE name_perso = ? AND owner_perso = ?");
$req->execute([$_SESSION['selected_perso'], $login]);
$dataFighter = $req->fetch();

$req = $pdo->prepare("SELECT * FROM persos WHERE id_perso = ?");
$req->execute([$_GET['id']]);
$dataAdversary = $req->fetch();

?>

<?php
    echo '<h3>' . $dataFighter['owner_perso'] . '(' . $dataFighter['name_perso'] . ') VS ' . $dataAdversary['owner_perso'] . '(' . $dataAdversary['name_perso'] . ') </h3>';
    ?>
<div class="fightIntroBox">
    <div class="fightIntroBoxWinner">
        <?php
        $dataPerso = $dataFighter;
        $persoBuilderDisplayValue = "NoLink";
        persoBuilder($dataPerso, $persoBuilderDisplayValue);
        ?>
    
    </div>
    <div class="returnButtonFight">
        <a href="fight.php">Retour à la liste des combats</a>
    </div>
    
    
    <div class="fightIntroBoxLooser">
        <?php
        $dataPerso = $dataAdversary;
        $persoBuilderDisplayValue = "NoLink";
        persoBuilder($dataPerso, $persoBuilderDisplayValue);
        ?>
    
    </div>
</div>

    <div class="fightProcessFinal">
    <?php
    
    echo '<h2>Résultat du Combat</h2>';
    echo '<p>' .$dataFighter['owner_perso'] . ' (' . $dataFighter['name_perso'] . ') attaque avec une puissance de ' . $dataFighter['power_perso'] . '</p>';
    echo '<p>' .$dataAdversary['owner_perso'] . ' (' . $dataAdversary['name_perso'] . ') défend avec une puissance de ' . $dataAdversary['power_perso'] . '</p>';

    if ($dataFighter['power_perso'] > $dataAdversary['life_perso'] && $dataAdversary['power_perso'] > $dataFighter['life_perso'] )
    {
        echo '<p>Les deux adversaires se sont entretués.</p>';
        $fightResult = "doubleKill";
        fightProcess($fightResult);
        exit;
    }
    
    if ($dataFighter['power_perso'] > $dataAdversary['power_perso'] )
    {
        $damage = $dataFighter['power_perso'] - $dataAdversary['power_perso'];
        echo '<p>' .$dataAdversary['owner_perso'] . ' (' . $dataAdversary['name_perso'] . ') encaisse ' . $damage . ' points de dégâts</p>';

        if ($dataAdversary['life_perso'] <= $damage)
        {
            echo '<p>' . $dataAdversary['owner_perso'] . ' (' . $dataAdversary['name_perso'] . ') n\'a pas survécu au combat.</p>';
            $fightResult = "adversaryKilled";
            fightProcess($fightResult);
            exit;
        }
        else
        {
            echo '<p>' . $dataFighter['owner_perso'] . ' (' . $dataFighter['name_perso'] . ') a gagné le combat.</p>';
            $fightResult = "fighterWin";
            fightProcess($fightResult);
            exit;
        }
    }

    if ($dataFighter['power_perso'] == $dataAdversary['power_perso'] )
    {
        echo '<p>Les deux combattants se sont affrontés avec courage, mais aucun n\'a pu se démarquer. Match nul.</p>';
        $fightResult = "draw";
        fightProcess($fightResult);
        exit;
    }
    
    else
    {
        {
            $damage = $dataAdversary['power_perso'] - $dataFighter['power_perso'];
            echo 'L\'attaquant encaisse ' . $damage . 'points de dégâts';

            if ($dataFighter['life_perso'] <= $damage)
        {
            echo '<p>' . $dataFighter['owner_perso'] . ' (' . $dataFighter['name_perso'] . ') n\'a pas survécu au combat.</p>';
            $fightResult = "fighterKilled";
        fightProcess($fightResult);
            exit; 
        }
        else
        {
            echo '<p>' . $dataAdversary['owner_perso'] . ' (' . $dataAdversary['name_perso'] . ') a gagné le combat.</p>';
            $fightResult = "aversaryWin";
        fightProcess($fightResult);
            exit;
        }
        }
    }
    ?> 
    
    </div>


<div class="fightIntroBox">
    
    <div class="fightIntroBoxWinner">
    
    </div>

    <div class="fightIntroBoxLooser">
    
    </div>
</div>
