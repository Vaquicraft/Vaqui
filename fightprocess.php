<?php

require 'functions.php';
getUserData();
menu();

?>

<div class="pageBuilder">
    <?php

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
        $persoBuilderDisplayValue = "noLink";
        $persoBuilderTarget = "player";   
        persoBuilderGlobal($dataPerso, $persoBuilderDisplayValue, $persoBuilderTarget);
        ?>
    
    </div>
    
    
    
    <div class="fightIntroBoxLooser">
        <?php
        $dataPerso = $dataAdversary;
        $persoBuilderDisplayValue = "noLink";
        $persoBuilderTarget = "player";   
        persoBuilderGlobal($dataPerso, $persoBuilderDisplayValue, $persoBuilderTarget);
        ?>
    
    </div>
</div>

    <div class="fightProcessFinal">
    <?php
    
    echo '<h2>Résultat du Combat</h2>';

    $xp = $dataFighter['xp_perso'];
    $xp = calcXPLevel($xp);
    $level = $dataXP['level'];

    $ninFighter = $dataFighter['nin_perso'] * rand(50,150) / 100 ;
    $taiFighter = $dataFighter['tai_perso'] * rand(50,150) / 100 ;
    $genFighter = $dataFighter['gen_perso'] * rand(50,150) / 100 ;
    $lifeFighter = $dataFighter['life_perso'];

    $ninAdversary = $dataAdversary['nin_perso'] * rand(50,150) / 100 ;
    $taiAdversary = $dataAdversary['tai_perso'] * rand(50,150) / 100 ;
    $genAdversary = $dataAdversary['gen_perso'] * rand(50,150) / 100 ;
    $lifeAdversary = $dataAdversary['life_perso'];

    $damageFighter = ($ninFighter + $taiFighter + $genFighter) * 3;
    $damageAdversary = ($ninAdversary + $taiAdversary + $genAdversary) * 3;

    $ninBattle = $ninFighter - $ninAdversary;
    $taiBattle = $taiFighter - $taiAdversary;
    $genBattle = $genFighter - $genAdversary;

    $ninDef = $ninBattle - $genBattle;
    $taiDef = $taiBattle - $ninBattle;
    $genDef = $genBattle - $taiBattle;

    $ninDamage = $ninBattle - $ninDef;
    $taiDamage = $taiBattle - $taiDef;
    $genDamage = $genBattle - $genDef;

    $fightDamage = ($ninDamage + $taiDamage + $genDamage) * 3;

    echo 'Puissance d\'attaque (Joueur) : ' . $damageFighter . '<br />';
    echo 'Puissance d\'attaque (Adversaire) : ' . $damageAdversary . '<br /><br />';
    echo 'Dommage infligés : ' . abs($fightDamage) . '<br />'; 
    
    
    if ($damageFighter > $lifeAdversary) 
    {
        $fightResult = "adversaryKilled";
    }
     elseif ($damageAdversary > $lifeFighter) 
    {
        $fightResult = "fighterKilled";
    }
     elseif ($damageFighter > $lifeAdversary && $damageAdversary > $lifeFighter) 
    {
        $fightResult = "doubleKill";
    }
     elseif ($fightDamage <= 2 && $fightDamage >= -2) 
    {
        $fightResult = "draw";
    }
     elseif ($fightDamage > 2) 
    {
        $fightResult = "fighterWin";
    }
     elseif ($fightDamage < -2) 
    {
        $fightResult = "adversaryWin";
    }

    fightProcess($fightResult);

    

    


    ?> 
    
    </div>

 
    <div class="returnButtonFight">
        <a href="fight.php">Retour à la liste des combats</a>
    </div>
  



</div>

<?php
footer();
?>


