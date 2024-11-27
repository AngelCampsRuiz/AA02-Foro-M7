<?php
session_start();
include('./BDD/conexion.php');

// Obtener preguntas y respuestas de la base de datos
$preguntas = [];
try {
    $sql = "SELECT p.id, p.titulo, p.descripcion, p.fecha_publicacion, u.nombre_usuario 
            FROM preguntas p 
            JOIN usuarios u ON p.usuario_id = u.id 
            ORDER BY p.fecha_publicacion DESC";
    $stmt = $conexion->query($sql);
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener preguntas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>
<body>
    <div class="menu-bar">
        <button onclick="location.href='./Paginas/mis_preguntas.php'">Mis Preguntas</button>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_real']); ?>!</span>
            <br>
            <br>
            <button onclick="location.href='cerrar_sesion.php'">Cerrar Sesión</button>
        <?php else: ?>
            <button onclick="location.href='login.php'">Iniciar Sesión</button>
            <button onclick="location.href='register.php'">Registrarse</button>
        <?php endif; ?>
        <button onclick="location.href='./Paginas/form_preguntas.php'">Hacer pregunta</button>
    </div>

    <div class="content">
        <h1>Preguntas Recientes</h1>
        <?php foreach ($preguntas as $pregunta): ?>
            <div class="pregunta">
                <h2><?php echo htmlspecialchars($pregunta['titulo']); ?></h2>
                <p><?php echo htmlspecialchars($pregunta['descripcion']); ?></p>
                <button onclick="location.href='./Paginas/ver_respuestas.php'">Ver respuestas</button>

                <p>Publicado por: <?php echo htmlspecialchars($pregunta['nombre_usuario']); ?> el <?php echo $pregunta['fecha_publicacion']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
