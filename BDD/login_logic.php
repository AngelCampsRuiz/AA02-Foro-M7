<?php
include('conexion.php');

function iniciarSesion($usuario, $contrasena) {
    global $conexion;

    // Verificar que los campos no estén vacíos
    if (empty($usuario) || empty($contrasena)) {
        return 'Por favor, completa todos los campos.';
    }

    try {
        // Consultar el usuario en la base de datos
        $sql = "SELECT id, nombre_real, contrasena FROM usuarios WHERE nombre_usuario = :usuario";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario_db && password_verify($contrasena, $usuario_db['contrasena'])) {
            // Credenciales correctas, iniciar sesión
            session_start();
            $_SESSION['usuario_id'] = $usuario_db['id'];
            $_SESSION['nombre_real'] = $usuario_db['nombre_real'];

            // Redirigir al inicio
            header('Location: ./index.php');
            exit();
        } else {
            // Credenciales incorrectas
            return 'Usuario o contraseña incorrectos.';
        }
    } catch (PDOException $e) {
        return 'Error en la base de datos: ' . $e->getMessage();
    }
}
?>
