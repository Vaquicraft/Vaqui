<?php
require 'functions.php';
check_session();
menu();

    $req = 'SELECT * FROM persos';
    $req = $pdo->prepare($req);
    $req->execute();
    $fighters = $req->fetchAll();

    echo '<h2>Liste des Combats </h2>';

    foreach ($fighters as $fighter)
    {
        if ($fighter['owner_perso'] == $login)
        {
            continue;
        }
        
        
        echo $fighter['owner_perso'];
        echo ' (';
        echo $fighter['name_perso'];
        echo ') ';
        echo $fighter['xp_perso'];
        echo 'xp       ';
        echo $fighter['win'];
        echo '/';
        echo $fighter['lose'];
        echo '/';
        echo $fighter['draw'];
        echo '/';
        echo $fighter['kills'];
        echo '<br />';
    }

?>