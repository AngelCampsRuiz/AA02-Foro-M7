<?php
session_start();
include('../BDD/conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    echo '<div class="alert alert-danger text-center">Debes iniciar sesión para ver tus preguntas.</div>';
    echo '<a href="../login.php" class="btn btn-primary d-block mx-auto" style="width: 200px;">Iniciar Sesión</a>';
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$preguntas = [];
try {
    $sql = "SELECT p.id, p.titulo, p.descripcion FROM preguntas p WHERE p.usuario_id = :usuario_id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener preguntas: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_pregunta'])) {
    $pregunta_id = $_POST['pregunta_id'];
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);

    try {
        // Actualizar la pregunta en la base de datos
        $sql = "UPDATE preguntas SET titulo = :titulo, descripcion = :descripcion WHERE id = :pregunta_id AND usuario_id = :usuario_id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':pregunta_id', $pregunta_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success text-center">Pregunta actualizada con éxito.</div>';
        } else {
            echo '<div class="alert alert-danger text-center">Error al actualizar la pregunta.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger text-center">Error en la base de datos: ' . $e->getMessage() . '</div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_pregunta'])) {
    $pregunta_id = $_POST['pregunta_id'];

    try {
        // Iniciar una transacción
        $conexion->beginTransaction();

        // Eliminar respuestas asociadas a la pregunta
        $sql = "DELETE FROM respuestas WHERE pregunta_id = :pregunta_id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':pregunta_id', $pregunta_id, PDO::PARAM_INT);
        $stmt->execute();

        // Eliminar la pregunta
        $sql = "DELETE FROM preguntas WHERE id = :pregunta_id AND usuario_id = :usuario_id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':pregunta_id', $pregunta_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar la transacción
        $conexion->commit();

        // Redirigir para recargar la página
        header("Location: mis_preguntas.php");
        exit();
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $conexion->rollBack();
        echo '<div class="alert alert-danger text-center">Error al eliminar la pregunta: ' . $e->getMessage() . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Preguntas</title>
    <link rel="stylesheet" href="../Styles/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Mis Preguntas</h1>

        <!-- Listado de preguntas -->
        <?php if (count($preguntas) > 0): ?>
            <?php foreach ($preguntas as $pregunta): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($pregunta['titulo']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($pregunta['descripcion']); ?></p>
                        <!-- Formulario para editar -->
                        <form method="POST" action="mis_preguntas.php">
                            <input type="hidden" name="pregunta_id" value="<?php echo $pregunta['id']; ?>">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Nuevo Título:</label>
                                <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($pregunta['titulo']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Nueva Descripción:</label>
                                <textarea name="descripcion" class="form-control" rows="4" required><?php echo htmlspecialchars($pregunta['descripcion']); ?></textarea>
                            </div>
                            <button type="submit" name="editar_pregunta" class="btn btn-warning">Editar Pregunta</button>
                            <button type="submit" name="eliminar_pregunta" class="btn btn-danger">Eliminar Pregunta</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No tienes preguntas publicadas aún.</p>
        <?php endif; ?>

        <!-- Botón de Volver -->
        <a href="../index.php" class="btn btn-secondary mt-3">Volver al inicio</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
