<?php
    session_start();
    $_SESSION['verpremios']=1;
    $_SESSION['verparticipantes']=0;
    header("Location: ../index.php");
?>
