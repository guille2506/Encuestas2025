$(function() {
    // Función que se ejecuta cuando se hace clic en el botón "Finalizar"
    $("#btn-finalizar").click(function() {
        var idempresa = $('#idempresa').val();
        var nombreempresa = $('#nombreempresa').val();
        var sexo = $('#sexo').children("option:selected").val();
        var edad = $('#edad').children("option:selected").val();
        var area = $('#area').children("option:selected").val();
        var antiguedad = $('#antiguedad').children("option:selected").val();
        var niveleducativo = $('#niveleducativo').children("option:selected").val();

        $('#mensaje').hide();
        var mensaje = '';

        // Validación de campos obligatorios
        if (sexo === undefined || sexo === '') {
            mensaje += 'Falta cargar género! ';
        }
        if (edad === undefined || edad === '') {
            mensaje += 'Falta cargar rango etario! ';
        }
        if (area === undefined || area === '') {
            mensaje += 'Falta cargar área donde trabaja! ';
        }
        if (antiguedad === undefined || antiguedad === '') {
            mensaje += 'Falta cargar antigüedad! ';
        }
        if (niveleducativo === undefined || niveleducativo === '') {
            mensaje += 'Falta cargar nivel educativo! ';
        }

        if (mensaje !== '') {
            $('#mensaje').text(mensaje);
            $('#mensaje').show();
            return false;
        }

        // Recolectar respuestas de los bloques
        var respBloque1 = [
            $("input[name='gridRadio1-1']:checked").val(),
            $("input[name='gridRadio1-2']:checked").val(),
            $("input[name='gridRadio1-3']:checked").val(),
            $("input[name='gridRadio1-4']:checked").val(),
            $("input[name='gridRadio1-5']:checked").val(),
            $("input[name='gridRadio1-6']:checked").val(),
            $("input[name='gridRadio1-7']:checked").val(),
            $("input[name='gridRadio1-8']:checked").val()
        ];
        var respBloque2 = [
            $("input[name='gridRadio2-1']:checked").val(),
            $("input[name='gridRadio2-2']:checked").val(),
            $("input[name='gridRadio2-3']:checked").val(),
            $("input[name='gridRadio2-4']:checked").val(),
            $("input[name='gridRadio2-5']:checked").val(),
            $("input[name='gridRadio2-6']:checked").val(),
            $("input[name='gridRadio2-7']:checked").val(),
            $("input[name='gridRadio2-8']:checked").val()
        ];
        var respBloque3 = [
            $("input[name='gridRadio3-1']:checked").val(),
            $("input[name='gridRadio3-2']:checked").val(),
            $("input[name='gridRadio3-3']:checked").val(),
            $("input[name='gridRadio3-4']:checked").val(),
            $("input[name='gridRadio3-5']:checked").val(),
            $("input[name='gridRadio3-6']:checked").val(),
            $("input[name='gridRadio3-7']:checked").val(),
            $("input[name='gridRadio3-8']:checked").val()
        ];
        var respBloque4 = [
            $("input[name='gridRadio4-1']:checked").val(),
            $("input[name='gridRadio4-2']:checked").val(),
            $("input[name='gridRadio4-3']:checked").val(),
            $("input[name='gridRadio4-4']:checked").val(),
            $("input[name='gridRadio4-5']:checked").val(),
            $("input[name='gridRadio4-6']:checked").val(),
            $("input[name='gridRadio4-7']:checked").val(),
            $("input[name='gridRadio4-8']:checked").val()
        ];
        var respBloque5 = [
            $("input[name='gridRadio5-1']:checked").val(),
            $("input[name='gridRadio5-2']:checked").val(),
            $("input[name='gridRadio5-3']:checked").val(),
            $("input[name='gridRadio5-4']:checked").val(),
            $("input[name='gridRadio5-5']:checked").val(),
            $("input[name='gridRadio5-6']:checked").val(),
            $("input[name='gridRadio5-7']:checked").val(),
            $("input[name='gridRadio5-8']:checked").val()
        ];

        var respLibres = [
            $('#respuestaLibre1').val(),
            $('#respuestaLibre2').val(),
            $('#respuestaLibre3').val(),
            $('#respuestaLibre4').val(),
            $('#respuestaLibre5').val()
        ];

        var bloques = [
            JSON.stringify(respBloque1),
            JSON.stringify(respBloque2),
            JSON.stringify(respBloque3),
            JSON.stringify(respBloque4),
            JSON.stringify(respBloque5)
        ];

        // Verificar que todos los bloques tengan respuestas válidas
        for (var i = 0; i < bloques.length; i++) {
            if (bloques[i].indexOf('null') !== -1) {
                mensaje = 'Faltan respuestas en el bloque ' + (i + 1);
                $('#mensaje').text(mensaje);
                $('#mensaje').show();
                console.log(mensaje);
                return false;
            }
        }

        // Enviar datos del encuestado
        $.ajax({
            type: "POST",
            url: "saveSurveyed.php",
            data: {
                idempresa: idempresa,
                nombreempresa: nombreempresa,
                sexo: sexo,
                edad: edad,
                area: area,
                antiguedad: antiguedad,
                niveleducativo: niveleducativo
            },
            success: function(resp) {
                console.log("saveSurveyed.php: ", resp);
                if (resp !== '') {
                    alert(resp);
                    return false;
                } else {
                    // Si no hubo problemas, enviar respuestas de todos los bloques
                    var mensajefinal = "";
                    for (var i = 0; i < bloques.length; i++) {
                        var nombrebloque = '#nombrebloque' + (i + 1);
                        var valorbloque = $(nombrebloque).val();

                        if (!valorbloque) {
                            mensaje = 'Falta valor para ' + nombrebloque;
                            $('#mensaje').text(mensaje);
                            $('#mensaje').show();
                            console.log(mensaje);
                            return false;
                        }

                        // Log the data being sent
                        console.log({
                            nombrebloque: valorbloque,
                            bloque: bloques[i]
                        });
                        var j = i + 1;
                        $.ajax({
                            type: "POST",
                            url: "saveAnswers.php",
                            data: {
                                nombrebloque: j + '. ' + valorbloque,
                                bloque: bloques[i],
                                respLibre: respLibres[i]
                            },
                            success: function(resp) {
                                mensajefinal += resp;
                            },
                            error: function(xhr, status, error) {
                                console.error("Error en saveAnswers.php: ", status, error);
                            }
                        });
                    }
                    console.log("saveAnswers.php: ", resp);
                    if (mensajefinal !== '') {
                        alert(mensajefinal);
                        return false;
                    } else {
                        $('#mensaje').hide();
                        $('#btn-siguiente').hide();
                        $('#btn-anterior').hide();
                        $('#btn-finalizar').hide();
                        $('#mensajeDespedida').show();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en saveSurveyed.php: ", status, error);
            }
        });

    });

    // Función para comprobar si todas las respuestas del último bloque están seleccionadas
    function checkLastBlockResponses() {
        var allAnswered = true;
        
        // Verificar las preguntas de opción múltiple (radio buttons)
        $("#bloque_5 input[type='radio']").each(function() {
            var name = $(this).attr("name");
            if ($("input[name='" + name + "']:checked").length === 0) {
                allAnswered = false;
            }
        });

        // Verificar también los campos de respuesta libre
        $("#bloque_5 textarea").each(function() {
            if ($(this).val().trim() === "") {
                allAnswered = false;
            }
        });

        if (allAnswered) {
            $('#btn-finalizar').show();
        } else {
            $('#btn-finalizar').hide();
        }
    }

    // Agregar eventos para verificar respuestas del último bloque
    $("#bloque_5 input[type='radio']").change(checkLastBlockResponses);
    $("#bloque_5 textarea").on('input', checkLastBlockResponses);

    // Ocultar el botón finalizar al inicio
    $('#btn-finalizar').hide();
});

function iniciarEncuesta() {
    // limpieza cache en caso de ser necesario
    location.reload(true);       // recargar la pagina y sus parámetros
    window.scrollTo(0, 0);       // top of page
}

