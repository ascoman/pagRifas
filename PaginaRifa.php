
<?php

    if (isset($_SESSION['nomcodigo']))
    {
      echo '<div class="w3-container w3-center w3-section my-panel-color w3-round-large">
        <h2>Rifa '.$_SESSION['idrifa'].' TODOS GANAN</h2>
        <p>'.$_SESSION['nomrifa'].'</p>
      </div>';

      echo '<div class="w3-container w3-section my-panel-color w3-round-large w3-center">
              <h1>PREMIOS</h1>';
      require "includes/carruselPremios.inc.php";
      echo '</div>';



      echo '<div class="w3-container w3-section my-panel-color w3-round-large">';

          echo '<div class="w3-container my-section-color w3-round-large w3-center" style="box-shadow:0 5px 10px 0">';
                if ($_SESSION['num_seleccionado'] > 0 )
                {
                  if  ($_SESSION['revela']== 0)
                  {
                    echo '<h1 >Tu numero seleccionado es el '.$_SESSION['num_seleccionado'].'!! SUERTE!!</h1>';
                  }
                  else {
                    echo '<h1 >Tu numero seleccionado es el '.$_SESSION['num_seleccionado'].'!! FELICIDADES!!</h1>';
                  }

                }
                else
                {
                  echo  '<h1 >Selecciona tu numero!!</h1>';
                }

                if ($_SESSION['rifa_liberada'] == 1 && $_SESSION['revela']== 0)
                {

                  echo '<form action="includes/revres.inc.php" method="post">
                                      <div class="w3-margin">
                                          <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="mostrarResult">Mostrar resultados</button>
                                      </div>
                            </form>';
                }

          echo  '</div>';

          require "includes/gridNumeros.inc.php";


      echo '</div>';
    }


      ?>
