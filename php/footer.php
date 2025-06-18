
    <footer>
        <?php
        $req=$pdo->prepare("SELECT * FROM persos WHERE owner_perso = ? AND name_perso = ?");
        $req->execute([$login, $globalDataUser['selected_perso']]);
        $dataPerso=$req->fetch();
        $persoBuilderDisplayValue = "noLink";
        $persoBuilderTarget = "player";   
        persoBuilderGlobal($dataPerso, $persoBuilderDisplayValue, $persoBuilderTarget);
        
        ?>
    </footer>
    <?php
