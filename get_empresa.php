<?php
session_start();
// Definir la ruta del archivo CSV
$archivo_csv = 'csvfiles/empresas.csv';

// Obtener la clave ingresada desde la solicitud GET
$claveIngresada = $_GET['clave'];

// Leer el archivo CSV y buscar la clave ingresada
if (($gestor = fopen($archivo_csv, "r")) !== FALSE) {
    // Saltar la cabecera
    fgetcsv($gestor, 1000, ",");
    while (($fila = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        if ($fila[2] === $claveIngresada) {
            // Encontramos la clave, devolvemos el id correspondiente
            $_SESSION["id_empresa"]=$fila[0];
            echo json_encode(array("encontrado" => true, "empresa" => $fila[0]));
            fclose($gestor);
            exit;
        }
    }
    fclose($gestor);
}

// No se encontró la clave, devolvemos un mensaje de error
echo json_encode(array("encontrado" => false));
?>
