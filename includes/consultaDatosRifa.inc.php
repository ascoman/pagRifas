<?php
session_start();

if (isset($_POST['cargardatosrifa'])){
require 'dbh.inc.php';
  $numrifa = $_POST['numrifa'];

  if(empty($numrifa) || $numrifa==0){
    header("Location: ../index.php?error=numrifa");
  }
  else{
    $sql ="CALL sp_consultar_datos_rifa (?);";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?error=sqlerror");
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "i", $numrifa);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result))
      {
        if ($row['des_rifa'] != "")
        {
          $_SESSION['idrifa'] = $numrifa;
          $_SESSION['numtotalnumeros'] = $row['num_boletos'];
          $_SESSION['nomrifa'] = $row['des_rifa'];
          $_SESSION['rifa_liberada'] = $row['opc_liberada'];
          $_SESSION['fec_creada'] = $row['fec_creacion'];
          $_SESSION['costo_boleto'] = $row['num_costo_boleto'];
          $_SESSION['numcodigosAsignados'] = $row['num_codigos_asignados'];
          $_SESSION['numcodigosPagados'] = $row['num_codigos_pagados'];
          $_SESSION['valor_total_rifa'] = $row['num_valor_total_rifa'];
          $_SESSION['valor_pagado'] = $row['num_valor_pagado'];

          header("Location: ../index.php?login=successRifa");
        }
        else
        {
          header("Location: ../index.php?error=rifa0");
        }
      }
      else {
        header("Location: ../index.php?error=nofetch");
      }
    }
  }
}
else {
  header("Location: ../index.php?error=nocarga");
  }
  exit();
 ?>
