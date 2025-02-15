<?php
require 'functions.php';
menu();
getUserData();

    echo  '<h2>Gestion des personnages de ' . $login . '</h2>';

  ?>

  <div class="persoBuilderGlobalSlide">
  <li>
  <?php


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

        ?>
      

      <?php
      $dataPerso = $perso;
      persoBuilderCurrentPerso($dataPerso);
      ?>
 
     
      <?php
    }
    ?>
  
  </div>

  
