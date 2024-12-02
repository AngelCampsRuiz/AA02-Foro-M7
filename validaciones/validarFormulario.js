document.addEventListener('DOMContentLoaded', function() {
    const tituloInput = document.getElementById('titulo');
    const preguntaTextarea = document.getElementById('pregunta');
    const submitButton = document.querySelector('button[type="submit"]');

    function validarCampos() {
        const titulo = tituloInput.value.trim();
        const pregunta = preguntaTextarea.value.trim();
        const esValido = titulo !== '' && pregunta !== '' && !contieneInyeccion(titulo) && !contieneInyeccion(pregunta);

        submitButton.disabled = !esValido;
    }

    function contieneInyeccion(texto) {
        const patron = /['"<>;]/;
        return patron.test(texto);
    }

    tituloInput.addEventListener('input', validarCampos);
    preguntaTextarea.addEventListener('input', validarCampos);

    validarCampos(); // Inicializa el estado del botón
}); 