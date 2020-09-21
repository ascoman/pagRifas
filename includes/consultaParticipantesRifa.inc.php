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
    $sql ="CALL sp_consultar_participantes (?);";
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

        while ( $row = mysqli_fetch_row($result))
        {

          echo  '<div class= "w3-border w3-border-black w3-section w3-round-large">';
              echo '<div class="w3-margin">
                    <h1><b>Codigo : '.$row[0].'</b></h1>
                    </div>';
              echo  '<div class= "w3-table arrange-horizontally vertical-center">';
                    if ($row[1] > 0 )
                    {
                          echo '<div class="w3-cell-middle w3-section">
                                  <h4><b>Nombre : '.$row[2].'</b></h4>
                                </div>';

                          if ($row[4] > 0)
                          {
                            $date = date_create($row[5]);
                            echo '<div class="w3-padding">
                                    <h4><b>Numero elegido : '.$row[4].'</b></h4>
                                  </div>';
                            echo '<div class="w3-padding">
                                    <h4><b>Fecha de seleccion : '.date_format($date, 'd/m/y').'</b></h4>
                                  </div>';
                        }

                        if ($row[6] > 0)
                        {


                        if ($row[3] > 0)
                        {
                          echo '<div class="panel w3-margin w3-cell-middle w3-section my-section-color ">
                                  <h4><b>PAGADO</b></h4>
                                </div>';

                        }
                        else
                        {
                            echo '<div class="w3-cell-middle w3-section">';
                            echo '<form action = "includes/borraParticipante.inc.php" method="post" onsubmit="return confirm(\'Confirma borrar participante?\');">
                                  <div class="w3-padding">
                                      <input type="hidden" name="idParticipante" value='.$row[1].'>
                                      <div class="w3-quarter">
                                        <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="borrarParticipante">Borrar participante</button>
                                      </div>
                                  </div>
                                </form>';
                            echo "</div>";

                            if ($_SESSION['rifa_liberada'] == 0)
                            {
                                echo '<div class="w3-cell-middle w3-section">';
                                echo '<form action = "includes/pagaParticipante.inc.php" method="post" onsubmit="return confirm(\'Confirma marcar participante como pagado?\');">
                                      <div class="w3-padding">
                                          <input type="hidden" name="idParticipante" value='.$row[1].'>
                                          <div class="w3-quarter">
                                            <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="pagarParticipante">Marcar pagado</button>
                                          </div>
                                      </div>
                                    </form>';
                                echo "</div>";
                          }
                        }

                        $picname = "picPrem/".$row[8];
                        echo '<div class="w3-card w3-section w3-round my-section-color" style="width:20%; min-width:150px">
                                <img title="'.$row[7].'" src="'.$picname.'" class="w3-image w3-round-large" alt="premio" style="width:100%"
                                onclick="onClick(this)" class="w3-hover-opacity">
                                '.$row[7].'

                              </div>';
                      }

                }
                else
                {
                //echo '<td>';
                    if ($_SESSION['rifa_liberada'] == 0)
                    {
                      echo '<div class="w3-margin">';
                        echo '<form action = "includes/asignaParticipante.inc.php" method="post">
                          <table class="w3-table ">
                          <tr>
                              <td><div class="w3-margin ">
                                  <input class="w3-input w3-border w3-border-black w3-hover-border-red w3-round" type="text" name="nomParticipante" placeholder="Nombre del participante" required>
                              </div></td>
                                <input type="hidden" name="numCodigo" value='.$row[0].'>
                            <td>  <div class="w3-margin ">
                                  <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="asignaParticipante">Asignar</button>
                              </div></td>
                              </tr>
                            </table>
                          </form>';
                    echo "</div>";///div2
                    }
                    else {
                      echo '<div class="w3-padding">
                              <h4><b>SIN VENDER</b></h4>
                            </div>';
                    }
                }
echo "</div>";///div2
        echo "</div>";///div2
    }

  }

}

 ?>
 <div id="modal01" class="w3-modal shadowfilter" onclick="this.style.display='none'" style="padding-top: 170px;">
        <div class="w3-modal-content w3-animate-top">
          <img id="img01" style="width:80%">
          <h4 id="txt01"></h4>
        </div>
</div>

 <script>
          function onClick(element)
          {
            document.getElementById("txt01").innerHTML = element.title;
            document.getElementById("img01").src = element.src;
            document.getElementById("modal01").style.display = "block";
          }
</script>
