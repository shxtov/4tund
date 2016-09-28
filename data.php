<?php
    require ("functions.php");
    //kas on sisseloginud, kui ei ole siis
    //suunata login lehele

    //kas ?logout on aadressireal
    if (isset($_GET['logout'])){
        session_destroy();
        header("Location: login.php");
    }

    if (!isset ($_SESSION["userId"])){
        header("Location: login.php");
    }

?>

<h1>Data</h1>
<p>Tere tulemast! <?=$_SESSION['email'];?></p>
<a href="?logout=1">Logi välja</a>
