<?php
session_start();
if (isset($_POST['borrarParticipante'])) {
  require 'dbh.inc.php';

  $iduParticipante = $_POST['idParticipante'];
  $idurifa = $_SESSION['idrifa'];

  if(empty($iduParticipante) ){
    header("Location: ../index.php?error=nombreParticipante");
  }
  else
  {
    $sql ="CALL sp_borrar_usuario (?, ?);";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?error=sqlerror");
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "ii", $idurifa, $iduParticipante);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      header("Location: ../index.php");

    }
  }
}
else {
  header("Location: ../index.php");
  }
  $connection->close();
  exit();
 ?>
}
 ?>
