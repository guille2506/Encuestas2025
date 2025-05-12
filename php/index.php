<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuestas Integracion V6</title>
</head>

<?php
  session_start();
  error_reporting(0);

  function obtenerDatosEmpresa($id) {
      $archivo = fopen("csvfiles/empresas.csv", "r");
      if ($archivo !== FALSE) {
          // Saltar la primera línea (cabecera)
          fgetcsv($archivo);
          while (($datos = fgetcsv($archivo, 1000, ",")) !== FALSE) {
              if ($datos[0] == $id) {
                  fclose($archivo);
                  return [
                      'nombre' => $datos[1],
                      'logo' => $datos[3],
                      'alumnos' => $datos[4]
                  ];
              }
          }
          fclose($archivo);
      }
      return null;
  }

  // Añade tus funciones para leer los otros CSVs aquí, similar a obtenerDatosEmpresa
  function leerDatosGenerales($archivo) {
      $datos = [];
      if (($handle = fopen($archivo, "r")) !== FALSE) {
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
              $datos[] = $data[0]; // Asume que el CSV tiene una columna relevante
          }
          fclose($handle);
      }
      return $datos;
  }

  function obtenerAlumnos($idempresa) {
    return leerDatosGenerales($idempresa.'/csvfiles/alumnos.csv');
  }

  function obtenerGeneros() {
      return leerDatosGenerales('csvfiles/generos.csv');
  }

  function obtenerEdades() {
      return leerDatosGenerales('csvfiles/edades.csv');
  }

  function obtenerAreas() {
      return leerDatosGenerales('csvfiles/areas.csv');
  }
  function obtenerAntiguedad() {
    return leerDatosGenerales('csvfiles/antiguedad.csv');
}

function obtenerNiveleducativo() {
    return leerDatosGenerales('csvfiles/niveleducativo.csv');
}

  function obtenerPreguntas($archivo) {
      return leerDatosGenerales($archivo);
  }

  function obtenerEscalas($archivo) {
      return leerDatosGenerales($archivo);
  }
?>  

<body>
<h4>version 6.2</h4>
<div class="main-container" id="logo" >

  <div class="header-container1">
    <div class="logo-container">
      <img class="logo" src="images/Logo2.png" heigth="80%" alt="El logo del instituto muestra sus tradicionales colores azul y rojo más la semblanza del Quijote, obra cumbre de del escritor Miguel de Cervantes Saavedra.">
    </div>
  </div>

  <div class="form-container1" id="contrasena_container">
    <form class="form" onsubmit="event.preventDefault(); verificarClave();">
        <h3>Encuestas Clima Laboral</h3>
        <div class="form-group">
            <label for="clave">Contraseña de la Empresa</label>
            <input required="" type="password" id="clave" placeholder="Ingresa tu clave"
            onchange="verificarClave()">
        </div>
        <button type="submit" class="form-submit-btn" style="display: block;">Ingresar</button>
        <p id="mensaje"></p>
        <small id="creditos"> &nbsp;&nbsp;Tecnicatura Superior en Recursos Humanos <br>
        Tecnicatura Superior en Desarrollo de Software</small>
        
    </form>
    
  </div>
  <br>
  


<div class="bienvenido-empresa " id="empresa" style="display: none;">
    <!-- Contenido de la empresa se inyectará aquí -->
</div>


<script>
 
    function ocultarElementos() {
        const logo = document.querySelector(".logo-container");
        const header = document.querySelector(".header-container1");
        const creditos = document.getElementById("creditos");
        if (logo && header) {
            logo.style.display = "none";
            header.style.display = "none";
            creditos.style.display = "none";
        }
    }


    function mostrarEmpresa(id) {
        ocultarElementos(); // Ocultar el logo y el encabezado
        document.getElementById("contrasena_container").style.display = "none";
        const empresaDiv = document.getElementById("empresa");
        empresaDiv.innerHTML = document.getElementById("empresa" + id).innerHTML;
        empresaDiv.style.display = "block";
    }

    function verificarClave() {
        const claveIngresada = document.getElementById("clave").value;
        const mensaje = document.getElementById("mensaje");
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const respuesta = JSON.parse(this.responseText);
                if (respuesta.encontrado) {
                    mostrarEmpresa(respuesta.empresa);
                } else {
                    mensaje.textContent = "Clave incorrecta. Inténtalo de nuevo.";
                }
            }
        };
        xhttp.open("GET", "get_empresa.php?clave=" + claveIngresada, true);
        xhttp.send();
    }

    document.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            verificarClave();
        }
    });
</script>

<?php
$empresas = ["AGC", "Naelf", "Castellanas", "Gaudium", "DelParque"];

foreach ($empresas as $index => $empresa) {
    $id = $index + 1;
    $datos = obtenerDatosEmpresa($id);
    if ($datos) {
        echo '<div class="logo-container1" id="empresa' . $id . '" style="display: none;">';
        echo '<h1 class="bienvenidos"> Bienvenidos! <br>' . $empresa . '</h1>';
        echo '<img class="header-container1 logo1" src="' . $datos['logo'] . '" class="imagen">';
        echo '<br>';
        echo '<b class="equipo-trabajo"> &nbsp;&nbsp;&nbsp; Equipo de trabajo:</b>';
        echo '<table class="equipo-trabajo">';
        $index = 0;
        foreach (obtenerAlumnos($id) as $alumno) {
            if ($index > 0) {
                echo '<th>' . $alumno . '</th>';
            }
            $index++;
        }
        echo '</table>';
        echo '<hr>'; // Línea horizontal
        

        //echo "numero de empresa: ".$id."<br>";
        echo '<a class="btn-iniciar" href="encuestas.php?id='.$id.'&nombre='.$empresa.'&logo='.$datos['logo'].'">Iniciar Encuesta &nbsp;&nbsp;<small><sup>V6.2</sup></small></a>';
        echo '</div>';
    }
}
?>






</div>

</body>
</html>