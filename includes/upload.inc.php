<?php
session_start();
if (isset($_POST['submit']))
{
  require 'dbh.inc.php';

  $file = $_FILES['file'];

  $numImagen = $_POST['numImagen'];
  $descripcion = $_POST['descPremio'];
  $subeImgRifa = $_POST['ImgRifa'];

  if (strlen($descripcion) <= 50 )
  {
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];


    $fileExt = explode('.', $fileName);
    $fileActualExt = mb_strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileActualExt, $allowed))
    {
      define ('SITE_ROOT', realpath(dirname(dirname(__FILE__))));

      if ($fileError == 0 )
      {
        if ($subeImgRifa == 0)
        {
          $fileNameNew = $_SESSION['idrifa']."-".$numImagen.".".$fileActualExt;

          $fileDestination = SITE_ROOT."/picPrem/".$fileNameNew;

          if (move_uploaded_file($fileTmpName, $fileDestination))
          {
            $sql ="CALL sp_crear_premio (?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($connection);

            if (!mysqli_stmt_prepare($stmt, $sql)){
              header("Location: ../altarifa.php?error=sqlerror");
            }
            else
            {

              mysqli_stmt_bind_param($stmt, "iiss", $_SESSION['idrifa'], $numImagen, $descripcion, $fileNameNew );

              mysqli_stmt_execute($stmt);
            }
          }
        }
        else
        {
          $fileNameNew = $_SESSION['idrifa']."-0.jpg";
          $fileDestination = SITE_ROOT."/picPrem/".$fileNameNew;

          move_uploaded_file($fileTmpName, $fileDestination);
        }

        header("Location: ../index.php?uploadOK");
      }
      else {
        echo "Error al subir archivo";
      }

    }
    else
    {
      echo "Archivo no permitido";
    }
  }
  else
  {
      echo '<script type="text/javascript">alert("Descripcion de premio pasa de 50 caracteres");history.go(-1);</script>';
  }
}
 ?>
