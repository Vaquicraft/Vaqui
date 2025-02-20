<?php
require 'functions.php';
menu();
?>
<div class="pageBuilder">
    <?php
$displayFightList = true;
getFights($displayFightList);

?>

</div>

<?php

footer();

?>