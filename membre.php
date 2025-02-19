    <?php

    require 'functions.php';

    getUserData();
    menu();
    ?>
    <div class="pageBuilder">
            <div class ="news">
            </div>
    </div>
    <?php


// $xp = $globalDataPerso['xp_perso'];
$xp = 250;
$dataXP = calcXPLevel($xp);

echo "Niveau actuel : " . $dataXP['level'] . "<br>";
echo "XP restant dans ce niveau : " . $dataXP['xpActuel'] . "<br>";
echo "XP requis pour le prochain niveau : " . $dataXP['xpRequis'] . "<br>";


$fullXP = $dataXP['xpActuel'] + $globalDataPerso['xp_perso'];

echo $fullXP;

    footer();

    ?>

