<?php
require 'functions.php';
getUserData();
menu();

    fightList();

    $req = 'SELECT * FROM persos ORDER BY xp_perso DESC';
    $req = $pdo->prepare($req);
    $req->execute();
    $fightList = $req->fetchAll();

    foreach ($fightList as $fighter)
    {
        if ($fighter['owner_perso'] == $login)
        {
            continue;
        }

        $idPerso = $fighter['id_perso'];
        updatePower($idPerso);
        fightListBuilder();

    }

?>