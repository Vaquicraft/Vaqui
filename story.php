<?php
require 'functions.php';
getUserData();
require 'links.php';

?>

<div class="storyMainPage">
    <h2 class="h2--title">
      Mode Histoire
    </h2>

    <div class="storyMainPageContent">
      <h3 class="h3--title">
        <?php
        echo 'Étape ' .$globalDataStory['mh_step'] .' - ' . $globalDataStory['mh_title'];
        ?>
      </h3>

      <div class="PageBuilderStorySpeak">
        <?php
          checkMhStep();
        ?>
      </div>

      <div class="storyDialogue">
        <?php
          echo $globalDataStory['mh_dialogue'];
        ?>
      </div>
      <a class="storyButtonFight" href="storyfight.php">Combattre !</a>

    </div>
</div>






