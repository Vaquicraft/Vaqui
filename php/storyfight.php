<?php
require 'functions.php';
require 'links.php';
getUserData();
checkMhStep();

?>

<div class="storyMainPage">
    <h2 class="h2--title">
      Mode Histoire - Combat
    </h2>

    <?php

        $persoBuilderDisplayValue = "noLink";
        $persoBuilderTarget = "player";
        $dataPerso = $globalDataPerso;
        $power = $globalDataPerso['power_perso'];

    ?>

    <div class="storyMainPageContent">
      <h3 class="h3--title">
        <?php



      persoBuilderGlobal($dataPerso, $persoBuilderDisplayValue, $persoBuilderTarget);

      echo '<h2>VS</h2>';
      $persoBuilderTarget = "story";

      persoBuilderGlobal($dataPerso, $persoBuilderDisplayValue, $persoBuilderTarget);
  
  
      if ($power >= $globalDataStory['mh_power'])
          {
              echo 'Vous avez remporté le combat !';
              echo 'Vous avez obtenu le personnage ' . $globalDataStory['mh_reward_1'];
              newPerso($globalDataStory['mh_reward_1']);
              $req = $pdo->prepare("UPDATE users SET user_mh_step = user_mh_step + 1 WHERE login = ?");
              $req->execute([($login)]);
          }
          else
          {
              echo 'Vous avez perdu le combat...';
          }
          ?>
</div>



