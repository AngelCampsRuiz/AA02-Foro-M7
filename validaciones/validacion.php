<?php

function validarUsuario($usuario) {
    return !empty($usuario) && preg_match("/^[a-zA-Z\s]+$/", $usuario);
}

function validarNombreReal($nombre_real) {
    return !empty($nombre_real) && preg_match("/^[a-zA-Z\s]+$/", $nombre_real);
}

function validarCorreo($correo) {
    return !empty($correo) && filter_var($correo, FILTER_VALIDATE_EMAIL);
}

function validarContrasena($contrasena) {
    return !empty($contrasena) && preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{4,}$/", $contrasena);
}

function sanitizarEntrada($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function validarRegistro($datos) {
    $errores = [
        'usuario' => '',
        'nombre_real' => '',
        'correo' => '',
        'contrasena' => '',
        'confirmar_contrasena' => ''
    ];

    $usuario = sanitizarEntrada($datos['usuario']);
    $nombre_real = sanitizarEntrada($datos['nombre_real']);
    $correo = sanitizarEntrada($datos['correo']);
    $contrasena = $datos['contrasena']; // No sanitizar contraseñas
    $confirmar_contrasena = $datos['confirmar_contrasena'];

    if (!validarUsuario($usuario)) {
        $errores['usuario'] = "El nombre de usuario no puede estar vacío y solo puede contener letras y espacios.";
    }
    if (!validarNombreReal($nombre_real)) {
        $errores['nombre_real'] = "El nombre real no puede estar vacío y solo puede contener letras y espacios.";
    }
    if (!validarCorreo($correo)) {
        $errores['correo'] = "Por favor, introduce un correo electrónico válido.";
    }
    if (!validarContrasena($contrasena)) {
        $errores['contrasena'] = "La contraseña debe tener al menos 4 caracteres, una mayúscula, una minúscula y un número.";
    }
    if ($contrasena !== $confirmar_contrasena) {
        $errores['confirmar_contrasena'] = "Las contraseñas no coinciden.";
    }

    return $errores;
}