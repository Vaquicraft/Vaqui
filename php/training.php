<?php
require 'functions.php';
getUserData();
require 'links.php';

    $trainingPerso = $_SESSION['selected_perso'];
    echo '<a href="training.php?training=1">Entraînement de Ninjutsu (+1) (1h00)</a><br />';
    echo '<a href="training.php?training=2">Entraînement de Taijutsu (+1) (1h30)</a><br />';
    echo '<a href="training.php?training=3">Entraînement de Genjutsu (+1) (2h00)</a><br />';
    echo '<br /><br />';
    echo '<a href="training.php?training=4">Entraînement complet (+1 de chaque) (4h00)</a><br />';
    echo '<a href="training.php?training=5">Entraînement intensif (+4 de chaque) (12h00)</a><br />';


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
                break;
            case 2:
                $nin = 0;
                $tai = 1;
                $gen = 0;
                break;
            case 3:
                $nin = 0;
                $tai = 0;
                $gen = 1;
                break;
            case 4:
                $nin = 1;
                $tai = 1;
                $gen = 1;
                break;
            case 5:
                $nin = 4;
                $tai = 4;
                $gen = 4;
                break;
            default:
                break;    

        }
    }
}



if (isset($_GET['training'])) {
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "");
    increase_stats();
}

?>