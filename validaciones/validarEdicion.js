document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        const tituloInput = form.querySelector('input[name="titulo"]');
        const descripcionTextarea = form.querySelector('textarea[name="descripcion"]');
        const originalTitulo = tituloInput.value;
        const originalDescripcion = descripcionTextarea.value;
        const editButton = form.querySelector('button[name="editar_pregunta"]');

        function validarEdicion() {
            const titulo = tituloInput.value.trim();
            const descripcion = descripcionTextarea.value.trim();
            const esValido = titulo !== originalTitulo || descripcion !== originalDescripcion;

            editButton.disabled = !esValido;
        }

        tituloInput.addEventListener('input', validarEdicion);
        descripcionTextarea.addEventListener('input', validarEdicion);

        validarEdicion(); // Inicializa el estado del botón
    });
}); 