<?php
require 'functions.php';
getUserData();
menu();
echo '<br /><h2>Mode Histoire</h2>';
echo '<h2>Étape ' .$globalDataStory['mh_step'] .' - ' . $globalDataStory['mh_title'] . '</h2>';
?>
<div class="PageBuilderStorySpeak">
<?php



  checkMhStep();

?>
<div class="storyDialogue">
    <?php
    echo $globalDataStory['mh_dialogue'] . '<br /><br />';
    ?>

</div>

<?php
echo '<a href="storyfight.php">Combattre !</a>';

?>

</div>

