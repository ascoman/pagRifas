<?php
//session_start();
require 'dbh.inc.php';

$numrifa = $_SESSION['idrifa'];

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
      echo '<style>
      .mySlides {display:none}
      </style>
      <body>';

echo '<div class="w3-content w3-display-container w3-center w3-mobile w3-padding" style="height:10%; width:15%">';/////d1

      $y = 0;
        while ( $row = mysqli_fetch_row($result))
        {
          $picname = "picPrem/".$row[2];
          echo '<div class="w3-card-4 w3-round-large mySlides my-section-color " >';////d2
                  echo '<img class="w3-round-large " src="'.$picname.'" style="width:100%;max-width:500px;max-height:500px; object-fit: cover">';
echo $row[1];

          echo '</div>';/////d2
          $y++;
        }

            echo '<button class="w3-button w3-display-left w3-black" onclick="plusDivs(-1)">&#10094;</button>
                  <button class="w3-button w3-display-right w3-black" onclick="plusDivs(1)">&#10095;</button>';


  echo         ' </div>';/////d1
      $x = 1;
      while ( $x <= $y )
      {
        echo '<span class="w3-badge demo w3-border w3-transparent w3-hover-white" onclick="currentDiv('.$x.')"></span>';
        $x++;
      }




  echo         '<script>
          var slideIndex = 1;
          showDivs(slideIndex);

          function plusDivs(n) {
            showDivs(slideIndex += n);
          }

          function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("mySlides");
            if (n > x.length) {slideIndex = 1}
            if (n < 1) {slideIndex = x.length}
            for (i = 0; i < x.length; i++) {
               x[i].style.display = "none";
            }
            x[slideIndex-1].style.display = "block";
          }
          </script>

          </body>';
        }
}
 ?>
