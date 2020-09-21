<?php
session_start();
if (isset($_POST['selectNumPart'])) {
  require 'dbh.inc.php';

  $numseleccionado = $_POST['numSelec'];
  $idurifa = $_SESSION['idrifa'];
  $idusuario = $_SESSION['idu_usuario'];


  if(empty($numseleccionado) ){
    header("Location: ../index.php?error=numSeleccionado");
  }
  else
  {
    $sql ="CALL sp_seleccionar_numero (?, ?, ?);";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?error=sqlerror");
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "iii", $idurifa, $idusuario, $numseleccionado );
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if ($row = mysqli_fetch_assoc($result))
      {
        if ($row[0] == 0)
        {
          $_SESSION['num_seleccionado'] = $numseleccionado;
        }
      }


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
