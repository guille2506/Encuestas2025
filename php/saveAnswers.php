<?php
include_once('classes/Answers.php');
$ans = new Answers();

if (isset($_POST['nombrebloque']) && isset($_POST['bloque'])) {
    $nombrebloque = $_POST['nombrebloque'];
    $bloque = $_POST['bloque']; // Array de bloques en formato JSON
    $respLibre = $_POST['respLibre'];
    // Debug: Dump received data
    //var_dump($nombrebloque, $bloque);

    $addQues = $ans->addAnswers($nombrebloque, array($bloque), $respLibre); // Convertir a array

    // if ($addQues) {
    //     echo "Respuestas guardadas correctamente.";
    // } else {
    //     echo "Error al guardar las respuestas.";
    // }
} else {
    echo "Faltan datos por enviar.";
}


?>
