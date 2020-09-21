<?php
session_start();

$_SESSION['idrifa'] = null;
$_SESSION['numtotalnumeros'] = null;
$_SESSION['nomrifa'] = null;
$_SESSION['rifa_liberada'] = null;
$_SESSION['fec_creada'] = null;
$_SESSION['numcodigosAsignados'] = null;
$_SESSION['numcodigosPagados'] = null;
$_SESSION['valor_total_rifa'] = null;
$_SESSION['valor_pagado'] = null;

header("Location: ../index.php");
 ?>
