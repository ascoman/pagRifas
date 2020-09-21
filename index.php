<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8" >
  <meta name="description" content="Pagina de rifas twoshop.mx" >
  <meta name=viewport content="width=device-width, initial-scale-1" >
  <link rel="stylesheet" href="w3.css">
  <title>rifas</title>
</head>

<body class="my-background-color" >
  <header class="container fixed-header w3-white w3-round-large">
        <nav>

                <?php

//echo '<div  class="arrange-horizontally">';//////////div 1 todo header
echo '<div  class="w3-row">';

                    echo '<div class="w3-col l2 m2 s2 w3-center href="http:\\www.twoshop.mx">
                      <img class="w3-image" src="img/logo.jpg" alt="logo" style : "height:100%" />
                    </div>';

                    echo '<div class="w3-rest w3-center" >
                      <img class="w3-image my-banner" src="img/BANNERTODOSGANAN.png" alt="banner" />
                        </div>';

                  if (isset($_SESSION['nomcodigo']))
                  {
                      echo '<div class="arrange-horizontally my-header-color w3-round-large w3-center">';
                                        echo '<div class="w3-row w3-center my-header-color w3-round-large">';

                                        if (isset($_SESSION['nomcodigo']))
                                        {
                                                if ($_SESSION['shown'] == 0)
                                                {
                                                  echo '<div class="w3-padding w3-animate-left my-section-color w3-round-xlarge">
                                                             Bienvenid@ '.$_SESSION['userName'].'
                                                          </div>';
                                                  $_SESSION['shown'] = 1;
                                                }
                                                else
                                                {
                                                  echo '<div class="w3-padding my-section-color w3-round-xlarge">
                                                            Bienvenid@ '.$_SESSION['userName'].'
                                                          </div>';
                                                }
                                        }
                                        echo '</div>';

                                echo '<form action="includes/logout.inc.php" method="post">
                                            <button class="w3-btn w3-margin-left w3-ripple w3-round-large my-accent-color" type="submit" name="solicita-logout">Salir</button>
                              </form>';
                      echo '</div>';
          }
          else
          {
              echo '<div class="w3-row w3-center my-header-color w3-round-large">';


                  echo '<form action="includes/login.inc.php" method="post">
                              <div class="arrange-horizontally">
                              <div>
                                  <input class="w3-input w3-border w3-border-black w3-hover-border-red w3-round" type="text" name="codigo" placeholder="Codigo de acceso." required/>
                              </div>
                              <div>
                                    <button class="w3-btn w3-ripple w3-round-large my-accent-color" type="submit" name="captura-codigo">Entrar</button>
                              </div>

                              </div>
                        </form>';

            echo '<div>';

          }

echo '</div>';//////////div 1
                ?>
        </nav>

  </header>
</body>
</html>

  <main >
    <div  class="w3-container">
    <?php

      if (isset($_SESSION['nomcodigo'])){
          if ($_SESSION['esAdmin'] == 1)
          {
            require "altarifa.php";
            require "modificarifas.php";
          }
          else
          {
            require "PaginaRifa.php";
          }
      }
      else
      {
        echo '<div class="my-pad-pics">';

          require "includes/muestraImagenesRifasActivas.inc.php";

       echo '</div>';
      }
  ?>
  </div>
</main>

<?php
  require "footer.php";
?>
