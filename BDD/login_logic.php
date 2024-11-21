<?php
session_start();
include('conexion.php');

function iniciarSesion($usuario, $contrasena) {
    global $conexion;
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario=:usuario";
    $stmt = $conexion->prepare($sql);
    $stmt->execute(['usuario' => $usuario]);

    if ($stmt->rowCount() > 0) {
        $usuario_bd = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($contrasena, $usuario_bd['contrasena'])) {
            $_SESSION['id_usuario'] = $usuario_bd['id'];
            header("Location: ./inicio.php");
            exit();
        }
    }
    return "El usuario o contraseña son incorrectos.";
}
?> 