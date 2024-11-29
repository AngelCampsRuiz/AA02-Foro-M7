<?php
session_start();
include('../BDD/conexion.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de pregunta no válido.";
    exit();
}

$pregunta_id = (int)$_GET['id'];

$pregunta = null;
$respuestas = [];
try {
    // Consultar la pregunta
    $sql_pregunta = "SELECT p.id, p.titulo, p.descripcion, p.fecha_publicacion, u.nombre_usuario 
                     FROM preguntas p 
                     JOIN usuarios u ON p.usuario_id = u.id 
                     WHERE p.id = :pregunta_id";
    $stmt_pregunta = $conexion->prepare($sql_pregunta);
    $stmt_pregunta->bindParam(':pregunta_id', $pregunta_id, PDO::PARAM_INT);
    $stmt_pregunta->execute();
    $pregunta = $stmt_pregunta->fetch(PDO::FETCH_ASSOC);

    if (!$pregunta) {
        echo "La pregunta no existe.";
        exit();
    }

    // Consultar las respuestas
    $sql_respuestas = "SELECT r.id, r.contenido, r.fecha_publicacion, u.nombre_usuario 
                       FROM respuestas r 
                       JOIN usuarios u ON r.usuario_id = u.id 
                       WHERE r.pregunta_id = :pregunta_id
                       ORDER BY r.fecha_publicacion DESC";
    $stmt_respuestas = $conexion->prepare($sql_respuestas);
    $stmt_respuestas->bindParam(':pregunta_id', $pregunta_id, PDO::PARAM_INT);
    $stmt_respuestas->execute();
    $respuestas = $stmt_respuestas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener datos: " . htmlspecialchars($e->getMessage());
    exit();
}

// Procesar nueva respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['usuario_id'])) {
        echo "<script>alert('Debes iniciar sesión para responder a una pregunta.');</script>";
    } else {
        $contenido = trim($_POST['contenido']);
        $usuario_id = $_SESSION['usuario_id'];

        if (!empty($contenido) && strlen($contenido) <= 500) {
            try {
                $conexion->beginTransaction();

                $sql_insert_respuesta = "INSERT INTO respuestas (contenido, usuario_id, pregunta_id) 
                                         VALUES (:contenido, :usuario_id, :pregunta_id)";
                $stmt_insert = $conexion->prepare($sql_insert_respuesta);
                $stmt_insert->bindParam(':contenido', $contenido, PDO::PARAM_STR);
                $stmt_insert->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
                $stmt_insert->bindParam(':pregunta_id', $pregunta_id, PDO::PARAM_INT);
                $stmt_insert->execute();

                $conexion->commit();

                header("Location: ver_respuestas.php?id=$pregunta_id");
                exit();
            } catch (PDOException $e) {
                $conexion->rollBack();
                echo "<div class='alert alert-danger'>Error al insertar respuesta: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        } else {
            echo "<script>alert('La respuesta debe tener entre 1 y 500 caracteres.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respuestas</title>
    <link rel="stylesheet" type="text/css" href="../Styles/styles.css">
</head>
<body>
    <div class="content pregunta">
        <h1><?php echo htmlspecialchars($pregunta['titulo']); ?></h1>
        <p><?php echo nl2br(htmlspecialchars($pregunta['descripcion'])); ?></p>
        <p>Publicado por: <?php echo htmlspecialchars($pregunta['nombre_usuario']); ?> el <?php echo htmlspecialchars($pregunta['fecha_publicacion']); ?></p>

        <?php if (isset($_SESSION['usuario_id'])): ?>
            <form method="POST" class="form-respuesta pregunta">
                <textarea name="contenido" rows="3" maxlength="500" placeholder="Escribe tu respuesta aquí..."  oninput="updateCharCount(this)"></textarea>
                <div id="charCount">0/500</div>
                <br>
                <button type="submit" class="btn-form-pregunta">Responder</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($respuestas)): ?>
            <div class="respuestas">
                <h3>Respuestas:</h3>
                <?php foreach ($respuestas as $respuesta): ?>
                    <div class="respuesta">
                        <p><?php echo nl2br(htmlspecialchars($respuesta['contenido'])); ?></p>
                        <p>Por: <?php echo htmlspecialchars($respuesta['nombre_usuario']); ?> el <?php echo htmlspecialchars($respuesta['fecha_publicacion']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay respuestas para esta pregunta.</p>
        <?php endif; ?>
        <a href="../index.php" class="btn-form-pregunta" style="width: 200px;">Volver al inicio</a>
    </div>

    <script src="../Js/contadorCaracteres.js"></script>
</body>
</html>
