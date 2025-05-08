<?php

$displayFightList = false;
getFights($displayFightList);

?>

    <header class="links">
        <ul class="linksList">
            <li class="link">
                <img src="images/home.png" alt="home_icon">
                <a href="membre.php">Accueil</a>
            </li>
            
            <li class="link">
                <img src="images/perso.png" alt="home_icon">
                <a href="perso.php">Info Perso</a>  
            </li>
    
            <li class="link">
                <img src="images/perso.png" alt="home_icon">
                <a href="listpersos.php">Liste des Persos</a>  
            </li>
            
            <li class="link">
                <img src="images/fist.png" alt="home_icon">
                <?php
                echo '<a href="fight.php"> Combats (<span style="color:white">' . $fightCount . '</span>) </a>';
    
                ?>
           
            </li>
            <li class="link">
                <img src="images/mh.png" alt="home_icon">
                <a href="story.php">Mode Histoire</a>  
            </li>
            
            <li class="link">
                <img src="images/training.png" alt="home_icon">
                <a href="training.php">Entraînements</a>
            </li>
            <li class="link">
                <img src="images/logout.png" alt="home_icon">
                <a href="logout.php">Déconnexion</a>
            </li>
    
        </ul>

    </header>
