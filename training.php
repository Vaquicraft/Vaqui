<?php
require 'functions.php';
check_session();
menu();

$req = $pdo->prepare("
    SELECT users.*, persos.*
    FROM users
    INNER JOIN persos ON users.login = persos.owner_perso
    WHERE users.login = ?
    ");
    $req->execute(array($login));
    $curent_perso = $req->fetch();
    $curent_perso = $curent_perso['selected_perso'];


    echo '<a href="training.php?perso=' . $curent_perso . '&training=1">Entraînement de Ninjutsu (+1) (1h00)</a><br />';
    echo '<a href="training.php?perso=' . $curent_perso . '&training=2">Entraînement de Taijutsu (+1) (1h30)</a><br />';
    echo '<a href="training.php?perso=' . $curent_perso . '&training=3">Entraînement de Genjutsu (+1) (2h00)</a><br />';
    echo '<br /><br />';
    echo '<a href="training.php?perso=' . $curent_perso . '&training=4">Entraînement complet (+1 de chaque) (4h00)</a><br />';
    echo '<a href="training.php?perso=' . $curent_perso . '&training=5">Entraînement intensif (+4 de chaque) (12h00)</a><br />';
    


if(isset($_GET['training']))
{
    if(is_numeric($_GET['training']))
    {
        switch ($_GET['training'])
        {
            case 1:
                $nin = 1;
                $tai = 0;
                $gen = 0;
                increase_stats();
                break;
            case 2:
                $nin = 0;
                $tai = 1;
                $gen = 0;
                increase_stats();
                break;
            case 3:
                $nin = 0;
                $tai = 0;
                $gen = 1;
                increase_stats();
                break;
            case 4:
                $nin = 1;
                $tai = 1;
                $gen = 1;
                increase_stats();
                break;
            case 5:
                $nin = 4;
                $tai = 4;
                $gen = 4;
                increase_stats();
                break;
            default:
                break;    

        }
    }
}



function increase_stats()
{
    global $pdo, $login, $nin, $tai, $gen, $perso_id, $data_perso, $id_perso;
    
    // Vérifier la connexion et la session
    check_session();

    $name_perso = $_GET['perso'];
    
    // Récupérer les stats du personnage
    $req = $pdo->prepare("SELECT * FROM persos WHERE name_perso = ? AND owner_perso = ?");
    $req->execute([$name_perso, $login]);
    $data_perso = $req->fetch();
    

    $ninjutsu = $data_perso['nin_perso'] + $nin;
    $taijutsu = $data_perso['tai_perso'] + $tai;
    $genjutsu = $data_perso['gen_perso'] + $gen;
    
    echo 'Votre entraînement a porté ses fruits !';
    $req = $pdo->prepare("UPDATE persos SET nin_perso = ?, tai_perso = ?, gen_perso = ? WHERE name_perso = ? AND owner_perso = ?");
    $success = $req->execute([$ninjutsu, $taijutsu, $genjutsu, $name_perso, $login]);    

}



?>