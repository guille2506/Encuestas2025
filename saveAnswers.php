<?php
session_start(); // NECESARIO

include_once('classes/Answers.php');
$ans = new Answers();

if (isset($_POST['nombrebloque']) && isset($_POST['bloque'])) {
    $nombrebloque = $_POST['nombrebloque'];
    $bloque = $_POST['bloque'];
    $respLibre = $_POST['respLibre'];

    // Log de depuración
    if (!isset($_SESSION["idencuestado"])) {
        error_log("⚠️ idencuestado no está seteado.");
    }

    $ans->addAnswers($nombrebloque, array($bloque), $respLibre);
} else {
    echo "Faltan datos por enviar.";
}
