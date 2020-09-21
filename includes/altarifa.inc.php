<?php
session_start();
if (isset($_POST['generarifa'])){
require 'dbh.inc.php';

  $nombrerifa = $_POST['nomrifa'];
  $preciorifa = $_POST['preciorifa'];
  $totalnumeros = $_POST['numtotalnumeros'];

  $_SESSION['nomrifa'] = $_POST['nomrifa'];
  $_SESSION['numtotalnumeros'] = $_POST['numtotalnumeros'];

  if(empty($totalnumeros) || empty($nombrerifa) || empty($preciorifa) ){
    header("Location: ../index.php?error=nombrerifa");
  }
  else if (!preg_match("/^[0-9]*$/", $totalnumeros)){
    header("Location: ../index.php?error=numrifa");
  }
  else if (strlen($nombrerifa) > 25 )
  {
    header("Location: ../index.php?error=nombrrifalargo");
  }
  else{

    $sql ="CALL sp_crear_rifa ( ?, ?, ? );";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?error=sqlerror");
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "sii", $nombrerifa, $totalnumeros, $preciorifa);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result))
      {
        if ($row['idu_rifa'] > 0)
        {
          $_SESSION['idrifa'] = $row['idu_rifa'];
          echo '<form method="POST" id="idu_da" action = "consultaDatosRifa.inc.php">
            <input type="hidden" value="'.$row['idu_rifa'].'" name="numrifa">
            <input type="hidden" value="1" name="cargardatosrifa">
            </form>';


          echo  "<script type=\"text/javascript\">

             document.forms['idu_da'].submit();

             </script>";
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
  header("Location: ../index.php");

  }
    exit();



 ?>
