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
            <button onclick="location.href='cerrar_sesion.php'">Cerrar Sesión</button>
        <?php else: ?>
            <button onclick="location.href='login.php'">Iniciar Sesión</button>
            <button onclick="location.href='register.php'">Registrarse</button>
        <?php endif; ?>
        <button onclick="location.href='./Paginas/form_preguntas.php'">Hacer pregunta</button>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="buscar_pregunta" placeholder="Buscar preguntas por título" value="<?php echo isset($_GET['buscar_pregunta']) ? htmlspecialchars($_GET['buscar_pregunta']) : ''; ?>">
            <input type="text" name="buscar_usuario" placeholder="Buscar usuarios por nombre" value="<?php echo isset($_GET['buscar_usuario']) ? htmlspecialchars($_GET['buscar_usuario']) : ''; ?>">
            <button type="submit">Buscar</button>
            <button type="button" onclick="window.location.href='index.php'">Resetear</button>
        </form>
    </div>
    <!-- Fin del formulario de búsqueda -->

    <div class="content">
        <h1>Preguntas Recientes</h1>
        <?php
        // Lógica de búsqueda
        if (isset($_GET['buscar_pregunta']) && !empty($_GET['buscar_pregunta'])) {
            $buscarPregunta = $_GET['buscar_pregunta'];
            $sql = "SELECT p.id, p.titulo, p.descripcion, p.fecha_publicacion, u.nombre_usuario 
                    FROM preguntas p 
                    JOIN usuarios u ON p.usuario_id = u.id 
                    WHERE p.titulo LIKE :buscarPregunta
                    ORDER BY p.fecha_publicacion DESC";
            $stmt = $conexion->prepare($sql);
            $stmt->execute(['buscarPregunta' => "%$buscarPregunta%"]);
            $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if (isset($_GET['buscar_usuario']) && !empty($_GET['buscar_usuario'])) {
            $buscarUsuario = $_GET['buscar_usuario'];
            $sqlUsuarios = "SELECT nombre_usuario, nombre_real FROM usuarios 
                            WHERE nombre_usuario LIKE :buscarUsuario OR nombre_real LIKE :buscarUsuario";
            $stmtUsuarios = $conexion->prepare($sqlUsuarios);
            $stmtUsuarios->execute(['buscarUsuario' => "$buscarUsuario%"]);
            $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Usuarios encontrados:</h2>";
            foreach ($usuarios as $usuario) {
                echo "<p>" . htmlspecialchars($usuario['nombre_usuario']) . " (" . htmlspecialchars($usuario['nombre_real']) . ")</p>";
            }
        }
        ?>

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
