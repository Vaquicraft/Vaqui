<?php
require 'functions.php';
check_session();
menu();

    
    

    echo  '<h2><br /><br /><br /><br />Gestion des personnages de ' . $login . '<br /></h2>';
    $req=$pdo->prepare("SELECT * FROM persos WHERE owner_perso=?");
    $req->execute([($login)]);
    $persos=$req->fetchAll();

    foreach ($persos as $perso)
    {
        
        if(isset($_GET['perso']))
        {
            if ($_GET['perso'] !== $perso['name_perso'])
          {
            continue;
          }
        else
        {
            echo 'Vous avez sélectionné ' . $_GET['perso'];
            $modified = $_GET['perso'];
            $req = $pdo->prepare("UPDATE users SET selected_perso = ? WHERE login = ?");
            $req->execute([$modified, $login]);
            header("Location: " . $_SERVER['PHP_SELF']);
            $_SESSION['selected_perso'] = $modified;
        }
        }
        
        
        


  

        $name_perso = $perso['name_perso'];
        echo '<a href="perso.php?perso=' . $perso['name_perso'] . '"><b><br /><br /><br /><br />' . $perso['name_perso'] . '</b></a>'; 
        echo '<br />Puissance : ' . power($name_perso);  
        echo '<br />Ninjutsu : ' . $perso['nin_perso'];
        echo '<br />Taijutsu : ' . $perso['tai_perso'];
        echo '<br />Genjutsu : ' . $perso['gen_perso'];
        echo '<br />Vie : ' . $perso['life_perso'];
        echo '<br /><br />';
    }

    $req = 'SELECT * FROM persos';
    $req = $pdo->prepare($req);
    $req->execute();
    $fighters = $req->fetchAll();

    echo '<h4>Liste des personnages disponibles pour combattre : </h4>';

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