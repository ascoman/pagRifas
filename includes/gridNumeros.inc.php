
<?php
require 'dbh.inc.php';

$idrifa = $_SESSION['idrifa'];
$idusuario = $_SESSION['idu_usuario'];


echo '<div>


<div class="w3-container  w3-row-padding w3-margin-top " >';

if(empty($idrifa) )
{

  header("Location: ../index.php?error=numrifa");
  exit();
}
else
{
  $sql ="CALL sp_consultar_numeros_rifa (?, ?);";
  $stmt = mysqli_stmt_init($connection);

  if (!mysqli_stmt_prepare($stmt, $sql))
  {
    header("Location: ../index.php?error=sqlerror");
    exit();
  }
  else
  {
    mysqli_stmt_bind_param($stmt, "ii", $idrifa, $idusuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo '<div class="my-center" style="width:80%">';

    echo '<div class="w3-row" >';

      while ( $row = mysqli_fetch_row($result))
      {

                    if ( $_SESSION['revela'] == 1 )
                    {
                      echo  '<div class="w3-col s3 m4r l5r w3-center  my-grid-margin w3-display-container" >
                              <img title= "'.$row[4].'" src="picPrem/'.$row[5].'" class="w3-round-large my-center2 num-sqare-format-in-prem" style:"display: block"
                              onclick="onClick(this)" class="w3-hover-opacity">
                              <img src="img/square-frame.png" class="num-sqare-format-out shadowfilter">';

                              if (  $row[0] == $row[1] )
                              {
                                echo  '<button id="fittext'.$row[0].'" class="tran-button-selected w3-display-middle w3-text-black num-sqare-text w3-round-xlarge" style:"pointer-events: none">'.$row[0].'</button>';///es el numero del usuario
                              }
                              else if ( $row[2] > 0 )
                              {
                                echo  '<button id="fittext'.$row[0].'" class="tran-button-disabled w3-display-middle w3-text-black num-sqare-text w3-round-xlarge" style:"pointer-events: none">'.$row[0].'</button>';////es el numero de otro usuario
                              }
                              else if ($row[1] >0 )
                              {
                                echo  '<button id="fittext'.$row[0].'" class="tran-button-noselec w3-display-middle w3-text-black num-sqare-text w3-round-xlarge" style:"pointer-events: none">'.$row[0].'</button>';////disponible pero ya no puede seleccionar
                              }

                    }
                    else
                    {
                      echo  '<div class="w3-col s3 m4r l5r w3-center  my-grid-margin w3-display-container" >
                              <img src="img/clover-framed-card.jpg" class="w3-round-large num-sqare-format-in " style:"display: block">
                              <img src="img/square-frame.png" class=" num-sqare-format-out shadowfilter">';



                    if (  $row[0] == $row[1] )
                    {
                      echo  '<button id="fittext'.$row[0].'" class="tran-button-selected w3-display-middle w3-text-black num-sqare-text w3-round-xlarge">'.$row[0].'</button>';///es el numero del usuario
                    }
                    else if ( $row[2] > 0 )
                    {
                      echo  '<button id="fittext'.$row[0].'" class="tran-button-disabled w3-display-middle w3-text-black num-sqare-text w3-round-xlarge">'.$row[0].'</button>';////es el numero de otro usuario
                    }
                    else if ($row[1] >0 )
                    {
                      echo  '<button id="fittext'.$row[0].'" class="tran-button-noselec w3-display-middle w3-text-black num-sqare-text w3-round-xlarge">'.$row[0].'</button>';////disponible pero ya no puede seleccionar
                    }
                    else
                    { //////////se puede seleccionar numero*/
                      echo  '<form action = "includes/seleccionaNumero.inc.php"  method="POST" onsubmit="return confirm(\'Desea seleccionar el numero '.$row[0].'?\');">
                                       <input type="hidden" value="'.$row[0].'" name="numSelec">
                                       <button id="fittext'.$row[0].'" class="tran-button w3-display-middle w3-text-black num-sqare-text w3-round-xlarge tran-buttonH" type="submit" name="selectNumPart">'.$row[0].'</button>
                                       </form>';
                    }
                }

                echo    "<script src=\"fittext.js\"></script>
                    <script type=\"text/javascript\">
                      fitText(document.getElementById('fittext".$row[0]."'), 0.3)
                    </script>";

              echo  '</div>';

          }
          echo  '</div>';
          echo  '</div>';
  }
}
echo '</div>
</div>';

 ?>
