<?php
session_start();
if (isset($_POST['pagarParticipante'])) {
  require 'dbh.inc.php';

  $iduParticipante = $_POST['idParticipante'];
  $idurifa = $_SESSION['idrifa'];

  if(empty($iduParticipante) ){
    header("Location: ../index.php?error=nombreParticipante");
  }
  else
  {
    $sql ="CALL sp_marca_pagado (?, ?);";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?error=sqlerror");
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "ii", $idurifa, $iduParticipante);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      echo '<form method="POST" id="idu_da2" action = "consultaDatosRifa.inc.php">
        <input type="hidden" value="'.$idurifa.'" name="numrifa">
        <input type="hidden" value="1" name="cargardatosrifa">
        </form>';


      echo  "<script type=\"text/javascript\">

         document.forms['idu_da2'].submit();

         </script>";

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
