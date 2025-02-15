<?php
require 'functions.php';
getUserData();
menu();

    $req = 'SELECT * FROM persos ORDER BY xp_perso DESC';
    $req = $pdo->prepare($req);
    $req->execute();
    $fighters = $req->fetchAll();

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
    

    foreach ($fighters as $fighter)
    {
        if ($fighter['owner_perso'] == $login)
        {
            continue;
        }

        $idPerso = $fighter['id_perso'];
        $xp = $fighter['xp_perso'];
        updatePower($idPerso);

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