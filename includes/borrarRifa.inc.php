<?php
session_start();
if (isset($_POST['borrarifa'])){
require 'dbh.inc.php';

  $numrifa = $_SESSION['idrifa'];
//  $_SESSION['nomrifa'] = $_POST['nomrifa'];
//
  if(empty($numrifa) ){
    header("Location: ../index.php?error=numrifa");
  }
  else{
    $sql ="CALL sp_eliminar_rifa (?);";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?error=sqlerror");
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "i", $numrifa);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      define ('SITE_ROOT', realpath(dirname(dirname(__FILE__))));

      $fileDestination = SITE_ROOT."/picPrem//".$numrifa."-0.jpg";
      unlink($fileDestination);

      while ( $row = mysqli_fetch_row($result))
      {
          $fileDestination = SITE_ROOT."/picPrem//".$row[0];

          unlink($fileDestination);
      }

      $_SESSION['idrifa'] = null;
      $_SESSION['numtotalnumeros'] = null;
      $_SESSION['nomrifa'] = null;
      $_SESSION['rifa_liberada'] = null;
      $_SESSION['fec_creada'] = null;
      $_SESSION['numcodigosAsignados'] = null;
      $_SESSION['numcodigosPagados'] = null;
      $_SESSION['valor_total_rifa'] = null;
      $_SESSION['valor_pagado'] = null;
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
