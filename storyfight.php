<?php
require 'functions.php';
getUserData();
menu();



checkMhStep();
echo '<h2>Mode Histoire - Combat</h2>';



$persoBuilderDisplayValue = false;
$dataPerso = $globalDataPerso;
$power = $globalDataPerso['power_perso'];

?>
<div class="pageBuilderStoryFight">
    <?php
    persoBuilder($dataPerso, $persoBuilderDisplayValue);

    echo '<h2>VS</h2>';

    persoBuilderStory();


    if ($power >= $globalDataStory['mh_power'])
        {
            echo 'Vous avez remporté le combat !';
            echo 'Vous avez obtenu le personnage ' . $globalDataStory['mh_reward_1'];
            new_perso();
            $req = $pdo->prepare("UPDATE users SET user_mh_step = user_mh_step + 1 WHERE login = ?");
            $req->execute([($login)]);
        }
        else
        {
            echo 'Vous avez perdu le combat...';
        }

    ?>
</div>



