<?php
session_start();
if (isset($_POST['liberarifa'])){
require 'dbh.inc.php';

  $numrifa = $_SESSION['idrifa'];
//  $_SESSION['nomrifa'] = $_POST['nomrifa'];

  if(empty($numrifa) ){
    header("Location: ../index.php?error=numrifa");
  }
  else{
    $sql ="CALL sp_liberar_rifa (?);";
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
        if ($row['usuarios_sin_Seleccionar'] == 0)
        {

          echo '<form method="POST" id="idu_da3" action = "consultaDatosRifa.inc.php">
            <input type="hidden" value="'.$numrifa.'" name="numrifa">
            <input type="hidden" value="1" name="cargardatosrifa">
            </form>';

          echo  "<script type=\"text/javascript\">

             document.forms['idu_da3'].submit();

             </script>";
        }
        else {

          echo '<script language="javascript">';
          echo 'alert("No se puede liberar ya que '.$row['usuarios_sin_Seleccionar'].' participantes aun no seleccionan numero" )';
          echo '</script>';

          echo '<form method="POST" id="idu_da3" action = "consultaDatosRifa.inc.php">
            <input type="hidden" value="'.$numrifa.'" name="numrifa">
            <input type="hidden" value="1" name="cargardatosrifa">
            </form>';

          echo  "<script type=\"text/javascript\">

             document.forms['idu_da3'].submit();

             </script>";
        }

      }

      header("Location: ../index.php");

    }
  }
}
else
{

  header("Location: ../index.php");
}
  $connection->close();
  exit();

 ?>
