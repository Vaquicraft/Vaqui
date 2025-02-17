<?php
require 'functions.php';
getUserData();
?>

<div class="baseFramePage">
    <?php
    ?>
        <nav class="links2">
        <?php
        links();
        ?>
        </nav>
        
    <?php
    ?>
    <footer>
    <?php
    $req=$pdo->prepare("SELECT * FROM persos WHERE owner_perso = ? AND name_perso = ?");
    $req->execute([$login, $dataUser['selected_perso']]);
    $dataPerso=$req->fetch();
    persoBuilderCurrentPerso($dataPerso);
    ?>
</footer>

</div>


