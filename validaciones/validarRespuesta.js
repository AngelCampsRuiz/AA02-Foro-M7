document.addEventListener('DOMContentLoaded', function() {
    const respuestaTextarea = document.querySelector('textarea[name="contenido"]');
    const submitButton = document.querySelector('button[type="submit"]');

    function validarRespuesta() {
        const respuesta = respuestaTextarea.value.trim();
        submitButton.disabled = respuesta === '';
    }

    respuestaTextarea.addEventListener('input', validarRespuesta);

    validarRespuesta(); // Inicializa el estado del botón
}); 