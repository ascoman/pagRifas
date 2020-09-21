<?php
session_start();

if (isset($_POST['captura-codigo']))
{
  require 'dbh.inc.php';

  $codigo = $_POST['codigo'];

  if(empty($codigo))
  {
      function_alert("Capturar codigo");
      echo "<script>window.location = 'http://rifas.twoshop.mx'</script>";
  }
  else if (!preg_match("/^[a-zA-Z0-9]*$/", $codigo))
  {
    header("Location: ../index.php");
  }
  else{

    $sql ="CALL sp_validar_codigo (?);";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql))
    {
      header("Location: ../index.php?error=sqlerror");
    }
    else{

      mysqli_stmt_bind_param($stmt, "s", $codigo);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result))
      {

        if ($row['opc_es_admin'] == 1)
        {
          $_SESSION['nomcodigo'] = $row['nom_usuario'];
          $_SESSION['userName'] = $row['nom_usuario'];
          $_SESSION['esAdmin'] = 1;
          $_SESSION['shown'] = 0;
          $_SESSION['verpremios'] = 0;

          header("Location: ../index.php?login=successAdmin");
        }
        else if ($row['opc_entra'] == 1)
        {

          $_SESSION['nomcodigo'] = $codigo;
          $_SESSION['userName'] = $row['nom_usuario'];
          $_SESSION['idrifa'] = $row['idu_rifa'];
          $_SESSION['esAdmin'] =0;
          $_SESSION['numtotalnumeros'] = $row['numeros_rifa'];
          $_SESSION['nomrifa'] = $row['nom_rifa'];
          $_SESSION['rifa_liberada'] = $row['opc_liberada'];
          $_SESSION['costo_boleto'] = $row['precio_boleto'];
          $_SESSION['boleto_pagado'] = $row['opc_pagado'];
          $_SESSION['idu_usuario'] = $row['idu_usuario'];
          $_SESSION['num_seleccionado'] = $row['num_seleccionado'];
          $_SESSION['shown'] = 0;
          $_SESSION['revela'] = 0;

          header("Location: ../index.php?login=success");

        }
        else
        {
          header("Location: ../index.php?error=nocode");
        }
      }
      else
      {
        $_SESSION['nomcodigo'] = '';
        $_SESSION['userName'] = '';
        $_SESSION['idrifa'] = 0;
        $_SESSION['esAdmin'] =0;
        $_SESSION['numtotalnumeros'] = 0;
        $_SESSION['nomrifa'] = '';
        $_SESSION['rifa_liberada'] = 0;
        $_SESSION['costo_boleto'] = 0;
        $_SESSION['boleto_pagado'] = 0;
        ;
        header("Location: ../index.php?error=nocode");
      }
    }
  }
}
else
{
  header("Location: ../index.php");
  }


  function function_alert($message)
  {

      // Display the alert box
     echo "<script>alert('$message');</script>";
  }
  $connection->close();
  exit();
 ?>
