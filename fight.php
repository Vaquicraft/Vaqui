<?php
require 'functions.php';
getUserData();
menu();

    

    $req = 'SELECT * FROM persos ORDER BY xp_perso DESC';
    $req = $pdo->prepare($req);
    $req->execute();
    $fightList = $req->fetchAll();

    $req = 'SELECT * FROM combat WHERE fighter_id = ?';
    $req = $pdo->prepare($req);
    $req->execute([$dataUser['id_perso']]);
    $dataCombat = $req->fetchAll();

    fightList();

    $xp = $dataUser['xp_perso'];
    $levelPerso = levelPerso($xp);

    

    $fightCount = 0;
    
    foreach ($fightList as $fighter)
    {

        if ($fighter['owner_perso'] == $login)
        {
            continue;
        }
        $skip = false;
        // $skipLevel = true;
        
        
        $xp = $fighter['xp_perso'];
        $levelFighter = levelPerso($xp);
        
        if ($levelPerso + 2 < $levelFighter || ($levelPerso - 2 > $levelFighter))
        {
            $skipLevel = true;
            
            if ($skipLevel)
            {
                continue;
            }
        }
        


        
        if(!empty($dataCombat))
        {
            foreach ($dataCombat as $value)
            {
                if ($fighter['id_perso'] == $value['adversary_id'])
                {
                    $skip = true;
                    break;      
                } 
            }    
            if ($skip)
            {
                continue;
                
        
            }
            
        }
        $fightCount++;

        

        $idPerso = $fighter['id_perso'];
        updatePower($idPerso);
        fightListBuilder();
    }
    echo $fightCount;

?>