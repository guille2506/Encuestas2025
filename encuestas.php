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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Encuesta Empresa</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/vendors.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="style_2">
<div id="preloader"><div data-loader="circle-side"></div></div>

<header>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-6 text-end">
            </div>
        </div>
    </div>
</header>

<div class="wrapper_centering">
    <div class="container_centering">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-6 col-lg-6 d-flex align-items-center">
                    <div class="main_title_1">
                        <h3><img src="images/recursos-logo11.png" width="80" height="80" alt=""> Bienvenido</h3>
                        <p>Por favor responda las siguientes preguntas con sinceridad. Sus respuestas son completamente anónimas y nos ayudarán a mejorar.</p>
                        <p><em>- Equipo de Encuestas</em></p>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5">
                    <div id="wizard_container">
                        <div id="top-wizard">
                            <div id="progressbar" style="background: #e9ecef; border-radius: 4px; height: 10px; width: 100%; margin-bottom: 20px;">
                            <div id="progressbar-value" style="background: #28a745; height: 100%; width: 0%; border-radius: 4px; transition: width 0.3s;"></div></div>
                        <form id="wrapped" method="POST">
                            <input type="hidden" id="idempresa" value="<?= htmlspecialchars($id_empresa) ?>">
                            <input type="hidden" id="nombreempresa" value="<?= htmlspecialchars($nombre_empresa) ?>">
                            <div id="middle-wizard">
                                <div class="step">
                                    <h3 class="main_question"><strong>1</strong> Datos del encuestado</h3>
                                    <?php
                                    function renderSelect($id, $label, $options) {
                                        echo "<div class='form-group'>";
                                        echo "<label for='{$id}'>{$label}</label>";
                                        echo "<select id='{$id}' name='{$id}' class='form-control required'>";
                                        echo "<option value=''>Seleccione una opción</option>";
                                        foreach ($options as $array) {
                                            foreach ($array as $val) {
                                                echo "<option value='" . htmlspecialchars($val) . "'>" . htmlspecialchars($val) . "</option>";
                                            }
                                        }
                                        echo "</select></div>";
                                    }

                                    renderSelect('sexo', 'Género', $fun->leergeneros($id_empresa.'/csvfiles/generos.csv'));
                                    renderSelect('edad', 'Edad', $fun->leeredades($id_empresa.'/csvfiles/edades.csv'));
                                    renderSelect('area', 'Área', $fun->leerareas($id_empresa.'/csvfiles/areas.csv'));
                                    renderSelect('antiguedad', 'Antigüedad', $fun->leerantiguedad($id_empresa.'/csvfiles/antiguedad.csv'));
                                    renderSelect('niveleducativo', 'Nivel educativo', $fun->leerniveleducativo($id_empresa.'/csvfiles/niveleducativo.csv'));
                                    ?>
                                </div>
                                <?php
                                $nro_bloque = 0;
                                $stepCount = 2;
                                $getBloques = $ques->getQuestions($id_empresa . '/csvfiles/bloques.csv');
                                if ($getBloques) {
                                    foreach ($getBloques as $bloque) {
                                        foreach ($bloque as $titulo) {
                                            $nro_bloque++;
                                            $oraciones = obtenerOraciones($id_empresa, $nro_bloque);
                                            $oraciones = array_slice($oraciones, 1); 

                                            $total = count($oraciones);
                                            $chunked = array_chunk($oraciones, 3); 

                                            foreach ($chunked as $grupo) {
                                                echo "<div class='step'>";
                                                echo "<h3 class='main_question'><strong>$stepCount</strong> $titulo</h3>";
                                                $i = 0;
                                                foreach ($grupo as $oracion) {
                                                    $i++;
                                                    $name = "bloque{$nro_bloque}_pregunta{$stepCount}_{$i}";
                                                    if (substr($oracion, -1) == "?") {
                                                        echo "<div class='form-group'><label>$oracion</label><textarea name='$name' class='form-control required' rows='3'></textarea></div>";
                                                    } else {
                                                        echo "<div class='form-group'><label>$oracion</label><div class='review_block_smiles'><ul class='clearfix'>";
                                                        $valor = 0;
                                                        foreach ($ques->getScales($id_empresa . '/csvfiles/escalas.csv') as $escalas) {
                                                            foreach ($escalas as $escala) {
                                                                $valor++;
                                                                $id_radio = "{$name}_{$valor}";
                                                                echo "<li><div class='container_smile'>";
                                                                echo "<input type='radio' id='$id_radio' name='$name' value='$valor' class='required'>";
                                                                echo "<label class='radio smile_$valor' for='$id_radio'><span>$escala</span></label>";
                                                                echo "</div></li>";
                                                            }
                                                        }
                                                        echo "</ul><div class='row justify-content-between add_bottom_25'><div class='col-4'><em>Muy Malo</em></div><div class='col-4 text-end'><em>Excelente</em></div></div></div></div>";
                                                    }
                                                }
                                                echo "</div>";
                                                $stepCount++;
                                            }
                                        }
                                    }
                                }
                                ?>
                                <div class="step">
                                    <h3 class="main_question">¡Gracias por su participación!</h3>
                                    <p>Redirigiendo en <span id="countdown">5</span> segundos...</p>
                                </div>
                            </div>
                            <div id="bottom-wizard">
                                <button type="button" name="backward" class="backward">Anterior</button>
                                <button type="button" name="forward" class="forward">Siguiente</button>
                                <button type="submit" name="process" class="submit">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-3.7.1.min.js"></script>
<script src="js/common_scripts.min.js"></script>
<script src="js/functions.js"></script>
<script>
$(document).ready(function () {
    let currentStep = 0;
    const steps = $(".step");

    function updateProgressBar(index) {
        const total = steps.length;
        const percent = Math.round((index + 1) / total * 100);
        $("#progressbar-value").css("width", percent + "%");
    }

    function showStep(index) {
        steps.hide().eq(index).fadeIn();
        $(".backward").toggle(index > 0);
        $(".forward").toggle(index < steps.length - 1);
        $(".submit").toggle(index === steps.length - 1);
        updateProgressBar(index); // <-- Esto actualiza la barra
    }

    function validateStep(index) {
        let valid = true;
        const current = steps.eq(index);
        current.find("select.required, textarea.required, input.required").each(function () {
            if ($(this).is(":radio")) {
                const name = $(this).attr("name");
                if (!$(`input[name='${name}']:checked`).length) valid = false;
            } else if (!$(this).val()) {
                valid = false;
            }
        });
        if (!valid) alert("Por favor complete todos los campos obligatorios antes de continuar.");
        return valid;
    }

    $(".forward").click(function () {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });

    $(".backward").click(function () {
        currentStep--;
        showStep(currentStep);
    });

    showStep(currentStep);
});
</script>
<script>
  window.addEventListener("load", () => {
    const preloader = document.getElementById("preloader");
    if (preloader) {
      preloader.classList.add("fade-out");
      setTimeout(() => {
        preloader.remove();
      }, 1000);
    }
  });
</script>
<script>
document.getElementById("wrapped").addEventListener("submit", function(e) {
    e.preventDefault();
    const datos = {
        idempresa: document.getElementById("idempresa").value,
        nombreempresa: document.getElementById("nombreempresa").value,
        sexo: document.getElementById("sexo").value,
        edad: document.getElementById("edad").value,
        area: document.getElementById("area").value,
        antiguedad: document.getElementById("antiguedad").value,
        niveleducativo: document.getElementById("niveleducativo").value
    };

    fetch("saveSurveyed.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: new URLSearchParams(datos)
    }).then(() => {
        const bloques = document.querySelectorAll(".step");
        bloques.forEach((bloque, index) => {
            const respuestas = [];
            const radios = bloque.querySelectorAll("input[type='radio']:checked");
            radios.forEach(r => respuestas.push(r.value));
            const texto = bloque.querySelector("textarea")?.value || "";

            fetch("saveAnswers.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: new URLSearchParams({
                    nombrebloque: "bloque_" + index,
                    bloque: JSON.stringify(respuestas),
                    respLibre: texto
                })
            });
        });

    });
});

</script>


<script>
document.getElementById("wrapped").addEventListener("submit", function(e) {
    e.preventDefault();

    const datos = {
        idempresa: document.getElementById("idempresa").value,
        nombreempresa: document.getElementById("nombreempresa").value,
        sexo: document.getElementById("sexo").value,
        edad: document.getElementById("edad").value,
        area: document.getElementById("area").value,
        antiguedad: document.getElementById("antiguedad").value,
        niveleducativo: document.getElementById("niveleducativo").value
    };

    fetch("saveSurveyed.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: new URLSearchParams(datos)
    }).then(() => {
        const bloquesRespuestas = {};
        const pasos = document.querySelectorAll(".step");

        pasos.forEach((bloque) => {
            const titulo = bloque.querySelector("h3.main_question")?.textContent.trim() || "";
            const match = titulo.match(/^\d+\s+(.*)/); 
            const nombreBloque = match ? match[1] : null;
            if (!nombreBloque) return;

            if (!bloquesRespuestas[nombreBloque]) {
                bloquesRespuestas[nombreBloque] = { respuestas: [], texto: "" };
            }

            const radios = bloque.querySelectorAll("input[type='radio']:checked");
            radios.forEach(r => bloquesRespuestas[nombreBloque].respuestas.push(r.value));

            const textarea = bloque.querySelector("textarea");
            if (textarea && textarea.value.trim()) {
                bloquesRespuestas[nombreBloque].texto = textarea.value.trim();
            }
        });

        Object.entries(bloquesRespuestas).forEach(([nombreBloque, datosBloque]) => {
            fetch("saveAnswers.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: new URLSearchParams({
                    nombrebloque: nombreBloque,
                    bloque: JSON.stringify(datosBloque.respuestas),
                    respLibre: datosBloque.texto
                })
            });
        });

        showThankYouModal();
    });
});

function showThankYouModal() {
    alert("¡Gracias por participar!");
    window.location.href = "index.php";
}
</script>

</body>
</html>
