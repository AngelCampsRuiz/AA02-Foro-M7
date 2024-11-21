function inicializarValidaciones() {
    const usuarioInput = document.querySelector('input[name="usuario"]');
    const nombreRealInput = document.querySelector('input[name="nombre_real"]');
    const correoInput = document.querySelector('input[name="correo"]');
    const contrasenaInput = document.querySelector('input[name="contrasena"]');

    usuarioInput.onblur = function() {
        validarUsuario(usuarioInput);
    };

    nombreRealInput.onblur = function() {
        validarNombreReal(nombreRealInput);
    };

    correoInput.onblur = function() {
        validarCorreo(correoInput);
    };

    contrasenaInput.onblur = function() {
        validarContrasena(contrasenaInput);
    };
}

function validarUsuario(input) {
    const errorSpan = input.nextElementSibling;
    if (input.value.trim() === '') {
        errorSpan.textContent = 'El nombre de usuario no puede estar vacío.';
    } else if (!/^[a-zA-Z\s]+$/.test(input.value)) {
        errorSpan.textContent = 'El nombre de usuario solo puede contener letras y espacios.';
    } else {
        errorSpan.textContent = '';
    }
}

function validarNombreReal(input) {
    const errorSpan = input.nextElementSibling;
    if (input.value.trim() === '') {
        errorSpan.textContent = 'El nombre real no puede estar vacío.';
    } else if (!/^[a-zA-Z\s]+$/.test(input.value)) {
        errorSpan.textContent = 'El nombre real solo puede contener letras y espacios.';
    } else {
        errorSpan.textContent = '';
    }
}

function validarCorreo(input) {
    const errorSpan = input.nextElementSibling;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (input.value.trim() === '') {
        errorSpan.textContent = 'El correo electrónico no puede estar vacío.';
    } else if (!emailPattern.test(input.value)) {
        errorSpan.textContent = 'Por favor, introduce un correo electrónico válido.';
    } else {
        errorSpan.textContent = '';
    }
}

function validarContrasena(input) {
    const errorSpan = input.nextElementSibling;
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{4,}$/;
    if (input.value.trim() === '') {
        errorSpan.textContent = 'La contraseña no puede estar vacía.';
    } else if (!passwordPattern.test(input.value)) {
        errorSpan.textContent = 'La contraseña debe tener al menos 4 caracteres, una mayúscula, una minúscula y un número.';
    } else {
        errorSpan.textContent = '';
    }
}

// Llama a la función para inicializar las validaciones
inicializarValidaciones();