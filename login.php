<?php
include('./BDD/login_logic.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $error = iniciarSesion($usuario, $contrasena);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <link rel="stylesheet" type="text/css" href="styles/auth.css">
</head>
<body>
    <div class="auth-container">
        <div id="login-form" class="visible">
            <h1>Iniciar Sesión</h1>
            <form method="POST" action="login.php">
                <input type="text" name="usuario" placeholder="Nombre de usuario">
                <br>
                <input type="password" name="contrasena" placeholder="Contraseña">
                <br>
                <input type="submit" value="Iniciar Sesión">
            </form>
            <?php if (!empty($error)): ?>
                <span class="error"><?php echo $error; ?></span>
            <?php endif; ?>
            <div class="toggle-link" onclick="location.href='register.php'">¿No tienes cuenta? Regístrate aquí</div>
        </div>
    </div>
</body>
</html> 