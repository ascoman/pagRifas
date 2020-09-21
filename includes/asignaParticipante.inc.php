<?php
session_start();
if (isset($_POST['asignaParticipante'])) {
  require 'dbh.inc.php';

  $nombreParticipante = $_POST['nomParticipante'];
  $numCodigoSel = $_POST['numCodigo'];
  $idurifa = $_SESSION['idrifa'];

  if(empty($nombreParticipante) ){
    header("Location: ../index.php?error=nombreParticipante");
  }
  else
  {
    $sql ="CALL sp_crear_usuario (?, ?, ?);";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?error=sqlerror");
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "iss", $idurifa, $numCodigoSel, $nombreParticipante);
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
