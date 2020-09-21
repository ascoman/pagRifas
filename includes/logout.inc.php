<?php

session_start();
$_SESSION['nomcodigo'] = null;
$_SESSION['userName'] = null;
session_unset();
session_destroy();
header("Location: ../index.php");
 ?>
