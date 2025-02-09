<?php
require 'functions.php';
check_login();
bdd_connexion();
menu();
power();
    echo  '<h2><br /><br /><br /><br />Gestion des personnages de ' . $login . '<br /></h2>';

    $req=$pdo->prepare("SELECT * FROM users WHERE login=?");
    $req->execute(array($login));
    $donnees=$req->fetch();


    $req=$pdo->prepare("SELECT * FROM persos WHERE owner_perso=?");
    $req->execute(array($login));
    $donnees=$req->fetch();

    $joueurs = 0;
    $fighters = 0;

    $req = 'SELECT * FROM persos';
    $req = $pdo->prepare($req);
    $req->execute();
    $fighters = $req->fetchAll();

    echo '<br /><br /><br /><br /><br />Nom du perso : ' . ucfirst($donnees['name_perso']);    
    echo '<br />Expérience : ' . $donnees['xp_perso'];    
    echo '<br />Ninjutsu : ' . $donnees['nin_perso'];
    echo '<br />Taijutsu : ' . $donnees['tai_perso'];
    echo '<br />Genjutsu : ' . $donnees['gen_perso'];
    echo '<br />Puissance : ' . $power;
    // echo '<br /><br />Nombre de joueurs : ' . $joueurs;
    // echo '<br />Nombre de combattants : ' . $fighters;

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