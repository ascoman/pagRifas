<?php
require 'dbh.inc.php';


    $sql ="CALL sp_consultar_rifas_activas (1);";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql))
    {
      header("Location: ../index.php?error=sqlerror");
      exit();
    }
    else
    {

      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      echo '<div class="w3-row-padding">';
      while ( $row = mysqli_fetch_row($result))
      {
          $picname = "picPrem/".$row[0]."-0.jpg";
          if (file_exists($picname))
          {

        echo  '<div class="w3-display-container w3-half w3-padding">
                  <a href="'.$picname.'" target="new" >
                        <div class="w3-card-4" >
                            <img src="'.$picname.'" class="w3-round-large" style="width:100%">
                              <div class="w3-display-topmiddle">
                                <div class="w3-container w3-center my-section-color w3-round-large">
                                  <h4><b>Rifa : '.$row[0].'</b></h4>
                                </div>
                            </div>
                      </div>
              </a>
          </div>';

          }
      }
echo  '</div>';
    }
 ?>
