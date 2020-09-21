<div class="w3-container w3-section my-panel-color w3-round-large">
<?php



  echo '<div class= "w3-container w3-center">';///div2

          if (isset($_SESSION['idrifa']))
          {

            echo '<h1 class= "w3-container w3-center">
                    Datos de rifa
                  </h1>';

              $date = date_create($_SESSION['fec_creada']);
              echo "<div class= \"w3-panel my-section-color w3-round-xlarge w3-center\">
                      <div>
                        Rifa # ".$_SESSION['idrifa']."
                      </div>
                      <div>
                        Nombre de rifa : ".$_SESSION['nomrifa']."
                      </div>
                      <div>
                        Liberada : ".$_SESSION['rifa_liberada']."
                      </div>
                      <div>
                        Fecha de creacion : ".date_format($date, 'd/m/y')."
                      </div>
                      <div>
                        De ".$_SESSION['numtotalnumeros']." numeros a $".$_SESSION['costo_boleto']." c/u
                      </div>
                      <div>
                        Con valor total de : $".$_SESSION['valor_total_rifa']."
                      </div>
                      <div>
                        Total pagado : $".$_SESSION['valor_pagado']."
                      </div>
                </div>";

                echo '<div class= "w3-table arrange-horizontally w3-padding">';///div1


                       echo '<div class="w3-padding">
                                <a class="w3-btn w3-ripple w3-round-large my-accent-color" href="includes/verpremios.inc.php"> Premios </a>
                              </div>';

                    echo '<div class="w3-padding">
                              <a class="w3-btn w3-ripple w3-round-large my-accent-color" href="includes/verparticipantes.inc.php"> Participantes </a>
                          </div>';

                   echo '<form action = "includes/cierrarifa.inc.php" method="post" onsubmit="return confirm(\'Confirma salir de la rifa?\');">
                         <div class="w3-padding">
                             <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="cerrarrifa">Cierra rifa</button>
                          </div>
                      </form>';

                 echo "<form action = \"includes/borrarRifa.inc.php\" method=\"post\" onsubmit=\"return confirm('Confirma borrar la rifa?');\">
                         <div class=\"w3-padding\">
                             <button class=\"w3-btn w3-ripple w3-round-large my-accent-color\" type=\"submit\" name=\"borrarifa\">Borrar rifa</button>
                        </div>
                      </form>";

              if ($_SESSION['rifa_liberada'] == 0)
              {
                  echo '<form action = "includes/liberaRifa.inc.php" method="post" onsubmit="return confirm(\'Confirma liberar la rifa?\');">
                          <div class="w3-padding">
                              <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="liberarifa">Liberar rifa</button>
                        </div>
                      </form>';

//echo '<div class= "w3-section w3-center w3-border w3-border-black w3-round-large">';///div1


                echo '<form action="includes/upload.inc.php" method="POST" enctype="multipart/form-data">
                            <div class="w3-padding">
                              <input class="w3-input w3-round-large my-accent-color" type="file" name="file">Agregar imagen rifa</input>
                            </div>
                            <input type="hidden" name="ImgRifa" value = 1 >
                            <input type="hidden" name="numImagen" value = 0>
                            <div class="w3-padding">
                              <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="submit">Agregar</button>
                            </div>
                    </form>';
//echo '</div>';
              }
//echo '<div class= "w3-padding w3-center w3-border w3-border-black w3-round-large">';///div1
                define ('SITE_ROOT', realpath(dirname(dirname(__FILE__))));
                $picname = $_SESSION['idrifa'].'-0.jpg';
                $fileDestination = "picPrem/".$picname;
                if (file_exists ( $fileDestination ))
                {
                  echo '<div class="w3-margin">';
                  echo '<a href="'.$fileDestination.'" target="new" >
                  <div class="w3-card-4 w3-round-xlarge" style="width:250px">
                          <img class="w3-round-xlarge" src="'.$fileDestination.'" alt="Person" style="width:100%">
                      </div>
                      </a>';
                      echo '</div>';///div1
                }

//echo '</div>';

          echo '</div>';///div1
          }
          else
          {


            echo '<h1>
                    Alta de rifa
                  </h1>';

            echo '<form action = "includes/altarifa.inc.php" method="post">
                    <div class= "w3-row w3-container">
                      <div class="w3-row">
                          <div class="w3-col w3-quarter w3-padding" >
                            <input class="w3-input w3-border w3-border-black w3-hover-border-red w3-round" type="text" name="nomrifa" placeholder="Nombre de la rifa" required>
                          </div>
                          <div class="w3-col w3-quarter w3-padding">
                            <input class="w3-input w3-border w3-border-black w3-hover-border-red w3-round" type="number" name="preciorifa" placeholder="Precio por boleto" required>
                          </div>
                          <div class="w3-col w3-quarter w3-padding" >
                            <input class="w3-input w3-border w3-border-black w3-hover-border-red w3-round" type="number" name="numtotalnumeros" placeholder="Cantidad de numeros" required>
                          </div>
                          <div class="w3-col w3-quarter w3-padding" >
                            <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="generarifa">Crear</button>
                          </div>
                    </div>
                  </div>
                </form>';


        }
    echo "</div>";
      ?>
</div>
