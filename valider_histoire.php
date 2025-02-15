<?php
require 'functions.php';
getUserData();
menu();



checkMhStep();

$power = $dataUser['power_perso'];
echo '<h2>Mode Histoire - Combat</h2>';
echo '' . $dataUser['mh_perso'];    
echo '<br />Puissance : ' . $power;  

echo '<h2>VS</h2>';
echo '' . $dataUser['mh_enemy'];    
echo '<br />Puissance : ' . $dataUser['mh_power'];


if ($power >= $dataUser['mh_power'])
    {
        echo 'Vous avez remporté le combat !';
        echo 'Vous avez obtenu le personnage ' . $dataUser['mh_reward_1'];
        new_perso();
        $req = $pdo->prepare("UPDATE users SET user_mh_step = user_mh_step + 1 WHERE login = ?");
        $req->execute([($login)]);
    }
    else
    {
        echo 'Vous avez perdu le combat...';
    }

?>