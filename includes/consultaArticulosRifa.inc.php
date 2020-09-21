<?php
require 'dbh.inc.php';

  $numrifa = $_SESSION['idrifa'];
//  $_SESSION['nomrifa'] = $_POST['nomrifa'];
  if(empty($numrifa) )
  {
    header("Location: ../index.php?error=numrifa");
    exit();
  }
  else
  {
    $sql ="CALL sp_consultar_premios (?);";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql))
    {
      header("Location: ../index.php?error=sqlerror");
      exit();
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "i", $numrifa);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $x = 1;
        while ( $row = mysqli_fetch_row($result))
        {
          echo  '<div class= "w3-border w3-border-black w3-section w3-round-large">';

          echo  '<div class= "w3-table arrange-horizontally">';
            $picname = "picPrem/".$row[2];
            if (file_exists($picname) && $row[2] != "")
            {

              echo '<div class="w3-card-4 w3-margin w3-round-large" style="width:20%">
                      <img src="'.$picname.'" class="w3-round-large"  alt="Item" style="width:100%"
                                                  onclick="onClick(this)" class="w3-hover-opacity">
                  </div>';

                  echo '<div class="w3-cell-middle">';

                    echo '<div class="w3-container">
                            <h4><b>'.$row[1].'</b></h4>
                          </div>';

                  echo '<form action="includes/borrapremio.inc.php" method="POST" enctype="multipart/form-data">
                          <div class="w3-margin">
                            <input type="hidden" name="numImagen" value='.$row[0].'>
                            <input type="hidden" name="nombreImagen" value='.$picname.'>

                              <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="borrapremio">Borrar</button>
                        </div>
                      </form>';

              echo '</div>';

            }
            else
            {
              echo '<div class="w3-card w3-margin" style="width:20%">
                      <img src="img/nopic.jpg" alt="Person" style="width:100%">
                  </div>';

                    echo '<div class="w3-cell-middle">';

                  echo '<form action="includes/upload.inc.php" method="POST" enctype="multipart/form-data">
                          <div class="table w3-margin w3-center">
                              <div class="w3-margin">
                                <input class="w3-input w3-round-large my-accent-color" type="file" name="file" required>
                              </div>

                              <div class="w3-margin">
                                <input class="w3-input w3-border w3-border-black w3-hover-border-red w3-round" type="text" name="descPremio" placeholder="Descripcion del premio" required>
                              </div>

                                <input type="hidden" name="ImgRifa" value= 0 >
                                <input type="hidden" name="numImagen" value='.$x.'>

                              <div class="w3-margin">
                                <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="submit">Agregar</button>
                              </div>

                        </div>
                      </form>';

                      echo '</div>';
            }

            $x++;
          echo "</div>";///div2
          echo "</div>";///div2
      }
    }
  }
 ?>
<div id="modal01" class="w3-modal shadowfilter" onclick="this.style.display='none'" style="padding-top: 170px;">
       <div class="w3-modal-content w3-animate-top">
         <img id="img01" style="width:60%">
       </div>
     </div>

<script>
         function onClick(element)
         {
           document.getElementById("img01").src = element.src;
element.src;
           document.getElementById("modal01").style.display = "block";
         }
     </script>
