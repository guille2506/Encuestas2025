<?php
session_start();
date_default_timezone_set("America/Argentina/Buenos_Aires");
require_once 'database.class.php';


class Surveyed{

    public function leergeneros($csvfile) {
        $fp = fopen($csvfile, 'r'); // generos
        $headers = fgetcsv($fp); // trae los titulos del csv
        $data = array();
        while (($row = fgetcsv($fp)) !== false) { $data[] = array_combine($headers, $row); }
        fclose($fp);
        $json = json_encode($data, JSON_PRETTY_PRINT); // genera archivo json
        $arraygeneros = json_decode($json, true); // crea un array desde el json
        return $arraygeneros;
    }

    public function leeredades($csvfile) {
        $fp = fopen($csvfile, 'r'); // edades
        $headers = fgetcsv($fp); // trae los titulos del csv
        $data = array();
        while (($row = fgetcsv($fp)) !== false) {
            $data[] = array_combine($headers, $row);
        }
        fclose($fp);
        $json = json_encode($data, JSON_PRETTY_PRINT); // genera archivo json
        $arrayedades = json_decode($json, true); // crea un array desde el json
        return $arrayedades;
    }

    public function leerareas($csvfile) {
        $fp = fopen($csvfile, 'r'); // areas
        $headers = fgetcsv($fp); // trae los titulos del csv
        $data = array();
        while (($row = fgetcsv($fp)) !== false) {
            $data[] = array_combine($headers, $row);
        }
        fclose($fp);
        $json = json_encode($data, JSON_PRETTY_PRINT); // genera archivo json
        $arrayareas = json_decode($json, true); // crea un array desde el json
        return $arrayareas;
    }

    public function leerantiguedad($csvfile) {
        $fp = fopen($csvfile, 'r'); // areas
        $headers = fgetcsv($fp); // trae los titulos del csv
        $data = array();
        while (($row = fgetcsv($fp)) !== false) {
            $data[] = array_combine($headers, $row);
        }
        fclose($fp);
        $json = json_encode($data, JSON_PRETTY_PRINT); // genera archivo json
        $arrayantiguedad = json_decode($json, true); // crea un array desde el json
        return $arrayantiguedad;
    }

    public function leerniveleducativo($csvfile) {
        $fp = fopen($csvfile, 'r'); // nivel educativo
        $headers = fgetcsv($fp); // trae los titulos del csv
        $data = array();
        while (($row = fgetcsv($fp)) !== false) {
            $data[] = array_combine($headers, $row);
        }
        fclose($fp);
        $json = json_encode($data, JSON_PRETTY_PRINT); // genera archivo json
        $arrayniveleducativo = json_decode($json, true); // crea un array desde el json
        return $arrayniveleducativo;
    }
    

    public function addSurveyed($idempresa,$nombreempresa,$sexo,$edad,$area,$antiguedad,$niveleducativo) {
        $objDB = new DataBase;
        $dt = new DateTime();
        $fechahora = $dt->format('YmdHis');
        $aux = array( 
            "idgrupo"=>$idempresa,  
            "empresa"=>$nombreempresa,
            "sexo"=>$sexo,  
            "edad"=>$edad,
            "area"=>$area,
            "antiguedad"=>$antiguedad,
            "niveleducativo"=>$niveleducativo,
            "fechahora"=>$fechahora,
        );
        $r = $objDB->Insert('encuestados', $aux);
        
        $_SESSION["idencuestado"] = $r['insert_id'];

    }
}
?>