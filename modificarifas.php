<div class="w3-container w3-section my-panel-color w3-round-large">
<?php
      echo '<div class= "w3-container w3-center">';///div2
            if (isset($_SESSION['idrifa']))
            {

              if ($_SESSION['verpremios']==1)
              {
                echo '<h1 class= "w3-container w3-center">
                        Premios
                      </h1>';

                echo '<div class= "w3-container w3-center">';///div1
                require "includes/consultaArticulosRifa.inc.php";
                echo "</div>";///div1
              }
              else
              {
                echo '<h1 class= "w3-container w3-center">
                        Participantes
                      </h1>';

                echo '<div class= "w3-container w3-center">';///div1
                require 'includes/consultaParticipantesRifa.inc.php';
                echo '</div>';///div1
              }
          }
          else
          {
                  echo '<h1>Carga rifa</h1>';

                echo '<form action = "includes/consultaDatosRifa.inc.php" method="post">
                          <div class= "w3-row w3-container">
                              <div class="w3-row">
                                <div class= "w3-col w3-quarter w3-padding">
                                        <input class="w3-input w3-border w3-border-black w3-hover-border-red w3-round" type="number" name="numrifa" placeholder="Numero de rifa" required>
                                </div>
                                <div class="w3-col w3-quarter w3-padding" >
                                        <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="cargardatosrifa">Cargar</button>
                                </div>
                          </div>
                        </div>
                    </form>';
        }
echo "</div>";
      ?>
</div>
