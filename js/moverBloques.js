document.addEventListener("DOMContentLoaded", function() {
    const btnSiguiente = document.querySelector('.btn-siguiente');
    const btnAnterior = document.querySelector('.btn-anterior');
    const btnFinalizar = document.querySelector('.btn-finalizar');
    const mensajeError = document.querySelector('.mensaje');
    const totalBloques = 5; totalBloques;  // Get the total number of blocks from PHP

    btnSiguiente.addEventListener('click', function() {
        const bloqueActual = parseInt(this.getAttribute('data-bloque'));
        const siguienteBloque = parseInt(this.getAttribute('data-siguiente'));

        if (validateResponses(bloqueActual)) {
            document.getElementById('bloque_' + bloqueActual).style.display = 'none';
            const nextBlock = document.getElementById('bloque_' + siguienteBloque);
            if (nextBlock) {
                nextBlock.style.display = 'block';
                this.setAttribute('data-bloque', siguienteBloque);
                this.setAttribute('data-siguiente', siguienteBloque + 1);
                btnAnterior.setAttribute('data-bloque', siguienteBloque);
                btnAnterior.setAttribute('data-anterior', bloqueActual);

                // Scroll to the top of the page
                window.scrollTo(0, 680);

                if (siguienteBloque > 1) {
                    btnAnterior.style.display = 'inline-block';
                }
                if (siguienteBloque === 5) {
                    btnSiguiente.style.display = 'none';
                    btnFinalizar.style.display = 'inline-block';
                }

                // Hide the error message
                mensajeError.style.display = 'none';
            } else {
                this.style.display = 'none'; // Ocultar el botón si no hay más bloques
            }
        } else {
            mensajeError.textContent = 'Por favor responda todas las preguntas antes de continuar.';
            mensajeError.style.display = 'block';
        }
    });

    btnAnterior.addEventListener('click', function() {
        const bloqueActual = parseInt(this.getAttribute('data-bloque'));
        const anteriorBloque = parseInt(this.getAttribute('data-anterior'));

        document.getElementById('bloque_' + bloqueActual).style.display = 'none';
        const prevBlock = document.getElementById('bloque_' + anteriorBloque);
        if (prevBlock) {
            prevBlock.style.display = 'block';
            this.setAttribute('data-bloque', anteriorBloque);
            this.setAttribute('data-anterior', anteriorBloque - 1);
            btnSiguiente.setAttribute('data-bloque', anteriorBloque);
            btnSiguiente.setAttribute('data-siguiente', bloqueActual);

            // Scroll to the top of the page
            window.scrollTo(0, 680);

            if (anteriorBloque === 1) {
                btnAnterior.style.display = 'none';
            }
            if (anteriorBloque < totalBloques) {
                btnSiguiente.style.display = 'inline-block';
                btnFinalizar.style.display = 'none';
            }

            // Hide the error message
            mensajeError.style.display = 'none';
        } else {
            this.style.display = 'none'; // Ocultar el botón si no hay más bloques
        }
    });

    function validateResponses(bloqueActual) {
        const bloque = document.getElementById('bloque_' + bloqueActual);
        const inputs = bloque.querySelectorAll('input[type="radio"]');
        const textarea = bloque.querySelector('textarea');
        let allAnswered = true;
        let names = new Set();
       
        inputs.forEach(input => {
            names.add(input.name);
        });
   
        names.forEach(name => {
            const radios = bloque.querySelectorAll('input[name="' + name + '"]');
            let isChecked = false;
            radios.forEach(radio => {
                if (radio.checked) {
                    isChecked = true;
                }
            });
            if (!isChecked) {
                allAnswered = false;
            }
        });
   
        // Verifica si el textarea está vacío
        if (textarea && textarea.value.trim() === '') {
            allAnswered = false;
        }

        // Verifica en el bloque 1 los datos generales
        if (bloqueActual == 1) {
            var sexo = $('#sexo').children("option:selected").val();
            var edad = $('#edad').children("option:selected").val();
            var area = $('#area').children("option:selected").val();
            var antiguedad = $('#antiguedad').children("option:selected").val();
            var niveleducativo = $('#niveleducativo').children("option:selected").val();

            if (sexo === undefined || sexo === '') { allAnswered = false;}
            if (edad === undefined || edad === '') {allAnswered = false;}
            if (area === undefined || area === '') {allAnswered = false;}
            if (antiguedad === undefined || antiguedad === '') {allAnswered = false;}
            if (niveleducativo === undefined || niveleducativo === '') {allAnswered = false;}
        }
   
        return allAnswered;
    }
});

