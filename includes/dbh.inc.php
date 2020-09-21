<?php

$servername = "localhost";
$dBUsername = "twoshopm_rifas_app";
$dBPassword = "MillyLand1App0";
$dBName = "twoshopm_rifas";

$connection = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$connection){
  die("Connection failed:".mysqli_connect_error());
}
 ?>
