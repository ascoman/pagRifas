<?php
session_start();
if (isset($_POST['borrapremio'])) {
  require 'dbh.inc.php';

  $numImagen = $_POST['numImagen'];
  $fileName = $_POST['nombreImagen'];


    define ('SITE_ROOT', realpath(dirname(dirname(__FILE__))));

      $fileDestination = SITE_ROOT."/".$fileName;

      if (unlink($fileDestination))
      {
        $sql ="CALL sp_borra_premio (?, ?);";
        $stmt = mysqli_stmt_init($connection);

        if (!mysqli_stmt_prepare($stmt, $sql)){
          header("Location: ../altarifa.php?error=sqlerror");
        }
        else
        {

          mysqli_stmt_bind_param($stmt, "ii", $_SESSION['idrifa'], $numImagen );

          mysqli_stmt_execute($stmt);
        }
      }

      header("Location: ../index.php?deleteOK");
}
 ?>
