<?php
session_start();
include('./BDD/conexion.php');
include('./validaciones/validacion.php');

// Arreglo para guardar mensajes de error
$errores = [
    'usuario' => '',
    'nombre_real' => '',
    'correo' => '',
    'contrasena' => '',
    'confirmar_contrasena' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errores = validarRegistro($_POST);

    // Si no hay errores, proceder con el registro
    if (empty(array_filter($errores))) {
        $usuario = sanitizarEntrada($_POST['usuario']);
        $nombre_real = sanitizarEntrada($_POST['nombre_real']);
        $correo = sanitizarEntrada($_POST['correo']);
        $contrasena = $_POST['contrasena']; // No sanitizar contraseñas

        $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

        $sql = "SELECT * FROM usuarios WHERE nombre_usuario=:usuario OR email=:correo";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['usuario' => $usuario, 'correo' => $correo]);

        if ($stmt->rowCount() > 0) {
            $errores['usuario'] = "El nombre de usuario o el correo electrónico ya están registrados.";
        } else {
            $consulta = "INSERT INTO usuarios (nombre_usuario, nombre_real, email, contrasena) VALUES (:usuario, :nombre_real, :correo, :contrasena)";
            $stmt = $conexion->prepare($consulta);
            if ($stmt->execute(['usuario' => $usuario, 'nombre_real' => $nombre_real, 'correo' => $correo, 'contrasena' => $contrasena_hash])) {
                echo "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                $errores['general'] = "Hubo un error en el registro.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <link rel="stylesheet" type="text/css" href="styles/auth.css">
</head>
<body>
    <div class="auth-container">
        <div id="register-form" class="visible">
            <h1>Registro</h1>
            <form method="POST" action="register.php">
                <input type="text" name="usuario" placeholder="Nombre de usuario" value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>">
                <span class="error"><?php echo $errores['usuario']; ?></span>
                <br>
                <input type="text" name="nombre_real" placeholder="Nombre real" value="<?php echo htmlspecialchars($_POST['nombre_real'] ?? ''); ?>">
                <span class="error"><?php echo $errores['nombre_real']; ?></span>
                <br>
                <input type="email" name="correo" placeholder="Correo electrónico" value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>">
                <span class="error"><?php echo $errores['correo']; ?></span>
                <br>
                <input type="password" name="contrasena" placeholder="Contraseña">
                <span class="error"><?php echo $errores['contrasena']; ?></span>
                <br>
                <input type="password" name="confirmar_contrasena" placeholder="Confirmar Contraseña">
                <span class="error"><?php echo $errores['confirmar_contrasena']; ?></span>
                <br>
                <input type="submit" value="Registrar">
            </form>
            <div class="toggle-link" onclick="location.href='login.php'">¿Ya tienes cuenta? Inicia sesión aquí</div>
        </div>
    </div>
    <script src="./validaciones/validacion.js"></script>
</body>
</html>