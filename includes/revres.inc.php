<?php
session_start();
if (isset($_POST['mostrarResult']))
{
    $_SESSION['revela'] = 1;
    header("Location: ../index.php");
}
 ?>
