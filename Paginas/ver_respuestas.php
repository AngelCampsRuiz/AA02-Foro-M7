<?php
session_start();
include('../BDD/conexion.php');

// Obtener preguntas y respuestas de la base de datos
$preguntas = [];
$respuestas = [];
try {
    // Consultar preguntas
    $sql_preguntas = "SELECT p.id, p.titulo, p.descripcion, p.fecha_publicacion, u.nombre_usuario 
                      FROM preguntas p 
                      JOIN usuarios u ON p.usuario_id = u.id 
                      ORDER BY p.fecha_publicacion DESC";
    $stmt_preguntas = $conexion->query($sql_preguntas);
    $preguntas = $stmt_preguntas->fetchAll(PDO::FETCH_ASSOC);

    // Consultar respuestas
    $sql_respuestas = "SELECT r.id, r.contenido, r.fecha_publicacion, u.nombre_usuario, r.pregunta_id 
                       FROM respuestas r 
                       JOIN usuarios u ON r.usuario_id = u.id 
                       ORDER BY r.fecha_publicacion DESC";
    $stmt_respuestas = $conexion->query($sql_respuestas);
    $respuestas = $stmt_respuestas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener datos: " . $e->getMessage();
}

// Agrupar respuestas por pregunta
$respuestas_por_pregunta = [];
foreach ($respuestas as $respuesta) {
    $respuestas_por_pregunta[$respuesta['pregunta_id']][] = $respuesta;
}

// Procesar nueva respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pregunta_id'])) {
    if (!isset($_SESSION['usuario_id'])) {
        echo "<script>alert('Debes iniciar sesión para responder a una pregunta.');</script>";
    } else {
        $pregunta_id = $_POST['pregunta_id'];
        $contenido = trim($_POST['contenido']);
        $usuario_id = $_SESSION['usuario_id'];

        if (!empty($contenido) && strlen($contenido) <= 500) {
            try {
                $sql_insert_respuesta = "INSERT INTO respuestas (contenido, usuario_id, pregunta_id) 
                                         VALUES (:contenido, :usuario_id, :pregunta_id)";
                $stmt_insert = $conexion->prepare($sql_insert_respuesta);
                $stmt_insert->bindParam(':contenido', $contenido, PDO::PARAM_STR);
                $stmt_insert->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
                $stmt_insert->bindParam(':pregunta_id', $pregunta_id, PDO::PARAM_INT);
                $stmt_insert->execute();
                echo "<script>alert('Respuesta publicada con éxito.'); location.reload();</script>";
            } catch (PDOException $e) {
                echo "Error al insertar respuesta: " . $e->getMessage();
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
    <title>Inicio</title>
    <link rel="stylesheet" type="text/css" href="../Styles/styles.css">
</head>
<body>

    <div class="content">
        <h1>Preguntas Recientes</h1>
        <?php foreach ($preguntas as $pregunta): ?>
            <div class="pregunta">
                <h2><?php echo htmlspecialchars($pregunta['titulo']); ?></h2>
                <p><?php echo htmlspecialchars($pregunta['descripcion']); ?></p>
                <p>Publicado por: <?php echo htmlspecialchars($pregunta['nombre_usuario']); ?> el <?php echo $pregunta['fecha_publicacion']; ?></p>

                <!-- Responder a la pregunta -->
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <form method="POST" class="form-respuesta">
                        <input type="hidden" name="pregunta_id" value="<?php echo $pregunta['id']; ?>">
                        <textarea name="contenido" rows="3" maxlength="500" placeholder="Escribe tu respuesta aquí..." required></textarea>
                        <br>
                        <button type="submit">Responder</button>
                    </form>
                <?php endif; ?>

                <!-- Mostrar respuestas -->
                <?php if (isset($respuestas_por_pregunta[$pregunta['id']])): ?>
                    <div class="respuestas">
                        <h3>Respuestas:</h3>
                        <?php foreach ($respuestas_por_pregunta[$pregunta['id']] as $respuesta): ?>
                            <div class="respuesta">
                                <p><?php echo htmlspecialchars($respuesta['contenido']); ?></p>
                                <p>Por: <?php echo htmlspecialchars($respuesta['nombre_usuario']); ?> el <?php echo $respuesta['fecha_publicacion']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
