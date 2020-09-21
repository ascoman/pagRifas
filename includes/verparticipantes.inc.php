<?php
    session_start();
    $_SESSION['verpremios']=0;
    $_SESSION['verparticipantes']=1;
    header("Location: ../index.php");
?>
