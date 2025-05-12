<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuestas Integracion V6</title>
</head>
<body>
<h4>version 6.2</h4>
<?php

session_start();
error_reporting(0);

$id_empresa = $_GET["id"];
$nombre_empresa = $_GET["nombre"];
$logo_empresa = $_GET["logo"];

include_once ('classes/Questions.php');
include_once ('classes/Surveyed.php');
$ques = new Questions();
$fun = new Surveyed();

function leerDatosGenerales($archivo) {
    $datos = [];
    if (($handle = fopen($archivo, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $datos[] = $data[0];
        }
        fclose($handle);
    }
    return $datos;
}

function obtenerOraciones($idempresa, $nro_bloque) {
    return leerDatosGenerales($idempresa . '/csvfiles/oraciones' . $nro_bloque . '.csv');
}

?>
<!-- Modal para ingresar contraseña -->
    

<div class="main-container" id="logo">

    <div class="modal-background " id ="passwordModal">
      <div class="modal-content ">
        <form id ="passwordForm">
          <legend><b>Ingrese la contraseña</b></legend>
          Contraseña: <input id="passwordInput" type="password" placeholder="Contraseña" required>
          <button class="submitButton" id="submitButton" disabled>Ingresar</button>
    
    
        </form>

      </div>

    </div>

    <div class="header-container">
        <div class="logo-container1">
            <img class="logo" src="<?= htmlspecialchars($logo_empresa) ?>" alt="El logo de la Empresa.">
        </div>
        <h4 class="mb-3">"El objetivo de esta encuesta es encontrar áreas de mejora en el funcionamiento de la compañía<br> y en la satisfacción de los colaboradores que forman parte de ella.<br> Les recordamos que esta encuesta es anónima y la información recogida será utilizada solo con fines educativos<br> de los alumnos del Instituto Superior Cervantes.¡Muchas gracias por su valioso tiempo!"</h4>
    </div>
    <input type="hidden" id="idempresa" value="<?= htmlspecialchars($id_empresa) ?>">
    <input type="hidden" id="nombreempresa" value="<?= htmlspecialchars($nombre_empresa) ?>">

    <div style="margin-top: 60px">
        <form>
            <select class="form-select mb-3" aria-label="Default select example" id="sexo">
                <option value="" selected>Seleccione género</option>                        
                <?php 
                $getData = $fun->leergeneros($id_empresa.'/csvfiles/generos.csv');
                if($getData){
                    foreach ($getData as $arraygeneros){
                        foreach ($arraygeneros as $gen){
                            ?>
                            <option value="<?= htmlspecialchars($gen) ?>">   
                                <h6 class="mb-4"><?= htmlspecialchars($gen) ?></h6>
                            </option>
                            <?php
                        }
                    }
                }
                ?>
            </select>
        </form>  
        <form>
            <select class="form-select mb-3" aria-label="Default select example" id="edad">
                <option value="" selected>Seleccione rango etario</option>                        
                <?php 
                $getData = $fun->leeredades($id_empresa.'/csvfiles/edades.csv');
                if($getData){
                    foreach ($getData as $arrayedades){
                        foreach ($arrayedades as $edad){
                            ?>
                            <option value="<?= htmlspecialchars($edad) ?>">   
                                <h6 class="mb-4"><?= htmlspecialchars($edad) ?></h6>
                            </option>
                            <?php
                        }
                    }
                }
                ?>
            </select>
        </form>        
        <form>
            <select class="form-select mb-3" aria-label="Default select example" id="area">
                <option value="" selected>Seleccione area en la que trabaja</option>                     
                <?php 
                $getData = $fun->leerareas($id_empresa.'/csvfiles/areas.csv');
                if($getData){
                    foreach ($getData as $arrayareas){
                        foreach ($arrayareas as $area){
                            ?>
                            <option value="<?= htmlspecialchars($area) ?>">   
                                <h6 class="mb-4"><?= htmlspecialchars($area) ?></h6>
                            </option>
                            <?php
                        }
                    }
                }
                ?>
            </select>
        </form> 
        <form>
    <select class="form-select mb-3" aria-label="Default select example" id="antiguedad">
        <option value="" selected>Seleccione antigüedad en la empresa</option>                        
        <?php 
        $getData = $fun->leerantiguedad($id_empresa.'/csvfiles/antiguedad.csv');
        if($getData){
            foreach ($getData as $arrayantiguedad){
                foreach ($arrayantiguedad as $antiguedad){
                    ?>
                    <option value="<?= htmlspecialchars($antiguedad) ?>">   
                        <h6 class="mb-4"><?= htmlspecialchars($antiguedad) ?></h6>
                    </option>
                    <?php
                }
            }
        }
        ?>
    </select>
</form>
<form>
    <select class="form-select mb-3" aria-label="Default select example" id="niveleducativo">
        <option value="" selected>Seleccione su nivel educativo</option>                        
        <?php 
        $getData = $fun->leerniveleducativo($id_empresa.'/csvfiles/niveleducativo.csv');
        if($getData){
            foreach ($getData as $arrayniveleducativo){
                foreach ($arrayniveleducativo as $niveleducativo){
                    ?>
                    <option value="<?= htmlspecialchars($niveleducativo) ?>">   
                        <h6 class="mb-4"><?= htmlspecialchars($niveleducativo) ?></h6>
                    </option>
                    <?php
                }
            }
        }
        ?>
    </select>
    </div> 


    <div id="bloquesPreguntas" class="bloques-container">
        <div id="bloques-container"> 
            <?php
            $nro_bloque = 0;
            $getBloques = $ques->getQuestions($id_empresa . '/csvfiles/bloques.csv');
            if ($getBloques) {
                foreach ($getBloques as $itembloq) {
                    foreach ($itembloq as $itemb) {
                        $nro_bloque++;
                        echo '<div id="bloque_' . $nro_bloque . '" style="display: ' . ($nro_bloque == 1 ? 'block' : 'none') . '">';
                        echo '<h4 class="mb-4 titilante">' . htmlspecialchars($nro_bloque . '. ' . $itemb) . '</h4>';
                        echo '<input type="hidden" id="nombrebloque' . $nro_bloque . '" value="' . htmlspecialchars($itemb) . '">';
                        
                        $index = 0;
                        $i = 0;
                        foreach (obtenerOraciones($id_empresa, $nro_bloque) as $oracion) {
                            if ($index > 0) {
                                $i++;
                                $asterisco = substr($oracion, -1);
                                $radios = "gridRadio" . $nro_bloque . "-" . $i;

                                echo '<h4 class="mb-4">' . htmlspecialchars($oracion) . '</h4>';
                                if (trim($asterisco) == '?') {
                                    ?>
                                    <form>
                                        <fieldset class="form-container row mb-3 ">
                                            <div class="col-sm-10 respuestaLibre">
                                                <label>Ingrese su respuesta</label>
                                                <textarea rows="3" cols="60" id="respuestaLibre<?= $nro_bloque ?>"></textarea>
                                                <input class="form-check-input" type="radio" name="<?= $radios ?>" value="0" checked style="display:none;">
                                            </div>
                                        </fieldset>
                                    </form>
                                    <?php
                                } else {
                                    ?>
                                    <form>
                                        <fieldset class="form-container row mb-3">
                                            <div class="col-sm-10">
                                                <?php
                                                $valor = 0;
                                                $getData2 = $ques->getScales($id_empresa . '/csvfiles/escalas.csv');
                                                foreach ($getData2 as $arrayescalas) {
                                                    foreach ($arrayescalas as $escala) {
                                                        $valor++;
                                                        ?>
                                                    <div class="form-check">
                                                        <label class="container">
                                                            <input class="form-check-input" type="radio" name="<?= $radios ?>" id="<?= $radios . '-' . $valor ?>" value="<?= $valor ?>">
                                                            <div class="checkmark"></div>
                                                            <span class="form-check-label"><?= htmlspecialchars($escala) ?></span>
                                                            </label>
                                                    </div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </fieldset>
                                    </form>
                                    <?php
                                }
                            }
                            $index++;
                        }
                        echo '</div>';
                    }
                }
            }
            ?>

            <br>
            <div class="button-container">
                <button type="button" class="btn btn-outline-success button-27 btn-anterior" style="display: none;" data-bloque="1" data-anterior="0" id="btn-anterior">Anterior</button>
                <button type="button" class="btn btn-outline-success button-27 btn-siguiente" data-bloque="1" data-siguiente="2" id="btn-siguiente">Siguiente</button>
                <br>
            </div>

            <div class="button-container"> 
            <button type="button" class="btn-finalizar" id="btn-finalizar" style="display: none;">Finalizar</button>
            </div>

            <br>
            
            <div class ="thank-you-modal-background" id="thankYouModal">
                <div class="thank-you-modal-content">
                    <p id="thankYouMessage">¡Gracias por participar en la encuesta!</p>
                    <p>Redirigiendo en <span id="countdown">5</span> segundos...</p>
                </div> 
            </div>

            <br>

        <div class="mensaje" style="display: none;"> 
            <label id="mensaje" ></label>
        </div>
            <br>
            <div class="despedida-container">
                <div id="mensajeDespedida" class="form-container1" style="display: none;">
                    <h4>Los datos fueron registrados exitosamente! Gracias por su valioso aporte.</h4>
                    <br>
                    <h4>De parte de : alumnos de las Tecnicaturas Gestión De Recursos Humanos y Desarrollo De Software</h4>
                    <br>      
                    <div class="logo-container">
                        <img class="logo" src="images/Logo2.png" heigth="80%" alt="El logo del instituto muestra sus tradicionales colores azul y rojo más la semblanza del Quijote, obra cumbre de del escritor Miguel de Cervantes Saavedra.">
                    </div>
                    <h5 style="color: dodgerblue">Siguiente encuestado por favor.</h5>
                    <div class="button-container">
                    <button type="button" class="btn btn-lg bg-white m-2 btn-iniciar" id="btn-reiniciar" onclick="iniciarEncuesta()">Ir al Inicio</button>
                    </div>
                </div>
                <br><br>
            </div>


        </div>
    </div>
</div>

<script src="js/moverBloques.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="js/myScript.js"></script>
<script>

  // Función para comprobar la contraseña
  window.onload = function () {
    document.getElementById("passwordModal").style.display = "block";
};

// Función para verificar la contraseña y cambiar el color del botón
function checkPassword() {
    var password = document.getElementById("passwordInput").value;
    var submitButton = document.getElementById("submitButton");

    if (password === "123456") {
        submitButton.classList.add("correct");
        submitButton.disabled = false;
    } else {
        submitButton.classList.remove("correct");
        submitButton.disabled = true;
    }
}

// Agrega un evento al input de contraseña para verificarla cada vez que cambie
document.getElementById("passwordInput").addEventListener("input", checkPassword);

// Agrega un evento al formulario de contraseña para ocultarlo y mostrar el contenido después de ingresar la contraseña correcta
document.getElementById("passwordForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita que se envíe el formulario
    var password = document.getElementById("passwordInput").value;

    if (password === "123456") {
        document.getElementById("passwordModal").style.display = "none"; // Oculta el contenedor de la contraseña
        document.getElementById("contentContainer").style.display = "block"; // Muestra el contenedor del contenido
    }
});
</script>
<script>
function showThankYouModal(){
  document.getElementById("thankYouModal").style.display="block";
  var countdownElement =  document.getElementById("countdown");
  var countdown = 5;

  var countdownInterval = setInterval(function(){
    countdown--;
    countdownElement.textContent = countdown;

    if (countdown <=0){
      clearInterval(countdownInterval);
      location.reload();
    }
  }, 1000)
}

</script>
</body>
</html>
