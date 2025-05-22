<?php
session_start();
date_default_timezone_set("America/Argentina/Buenos_Aires");
require_once 'database.class.php';

class Answers {

    public function addAnswers($nombrebloque, $bloques, $respLibre) {
        $objDB = new DataBase;
        $dt = new DateTime();
        $fechahora = $dt->format('YmdHis');

        foreach ($bloques as $nro_bloque) {
            $bloque = json_decode($nro_bloque, true);
            $aux = array( 
                "idencuestado" => $_SESSION["idencuestado"],  
                "bloque" => $nombrebloque,
                "respuesta1" => $bloque[0],
                "respuesta2" => $bloque[1],
                "respuesta3" => $bloque[2],
                "respuesta4" => $bloque[3],  
                "respuesta5" => $bloque[4],
                "respuesta6" => $bloque[5],
                "respuesta7" => $bloque[6],
                "respuesta8" => $bloque[7],
                "respuestatext" => $respLibre,
                "fechahora" => $fechahora,
            );
            $objDB->Insert('respuestas', $aux);
        }
    }    
}

?>
