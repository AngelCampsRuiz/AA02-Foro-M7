<?php
session_start();
include('../BDD/conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?login_required=true");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editar_pregunta'])) {
        $pregunta_id = $_POST['pregunta_id'];
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);

        // Validación del lado del servidor
        if (empty($titulo) || empty($descripcion)) {
            echo '<div class="alert alert-danger text-center">Los campos no pueden estar vacíos.</div>';
        } else {
            try {
                // Actualizar la pregunta en la base de datos
                $sql = "UPDATE preguntas SET titulo = :titulo, descripcion = :descripcion WHERE id = :pregunta_id AND usuario_id = :usuario_id";
                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->bindParam(':pregunta_id', $pregunta_id, PDO::PARAM_INT);
                $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
                $stmt->execute();

                echo '<div class="alert alert-success text-center">Pregunta actualizada con éxito.</div>';
            } catch (PDOException $e) {
                echo '<div class="alert alert-danger text-center">Error al actualizar la pregunta: ' . $e->getMessage() . '</div>';
            }
        }
    } elseif (isset($_POST['eliminar_pregunta'])) {
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

            echo '<div class="alert alert-success text-center">Pregunta eliminada con éxito.</div>';
        } catch (PDOException $e) {
            // Rollback
            $conexion->rollBack();
            echo '<div class="alert alert-danger text-center">Error al eliminar la pregunta: ' . $e->getMessage() . '</div>';
        }
    }
}

// Consultar preguntas actualizadas 
try {
    $sql = "SELECT p.id, p.titulo, p.descripcion FROM preguntas p WHERE p.usuario_id = :usuario_id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener preguntas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Preguntas</title>
    <link rel="stylesheet" href="../Styles/estilos.css">
    <link rel="stylesheet" href="../Styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validarFormulario(form) {
            const titulo = form.titulo.value.trim();
            const descripcion = form.descripcion.value.trim();
            const botonEditar = form.querySelector('button[name="editar_pregunta"]');
            botonEditar.disabled = !(titulo && descripcion);
        }
    </script>
</head>

<body>
    <div class="container my-5 pregunta">
        <h1 class="text-center mb-4">Mis Preguntas</h1>

        <!-- Listado de preguntas -->
        <?php if (count($preguntas) > 0): ?>
            <?php foreach ($preguntas as $pregunta): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($pregunta['titulo']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($pregunta['descripcion']); ?></p>
                        <!-- Formulario para editar -->
                        <form method="POST" action="mis_preguntas.php" oninput="validarFormulario(this)">
                            <input type="hidden" name="pregunta_id" value="<?php echo $pregunta['id']; ?>">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Nuevo Título:</label>
                                <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($pregunta['titulo']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Nueva Descripción:</label>
                                <textarea name="descripcion" class="form-control" rows="4" maxlength="500"><?php echo htmlspecialchars($pregunta['descripcion']); ?></textarea>
                            </div>
                            <button type="submit" name="editar_pregunta" class="btn btn-warning" disabled>Editar Pregunta</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-<?php echo $pregunta['id']; ?>">Eliminar Pregunta</button>
                        </form>
                    </div>
                </div>

                <div class="modal fade" id="confirmModal-<?php echo $pregunta['id']; ?>" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ¿Estás seguro de que deseas eliminar esta pregunta?
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="mis_preguntas.php" style="display:inline;">
                                    <input type="hidden" name="pregunta_id" value="<?php echo $pregunta['id']; ?>">
                                    <button type="submit" name="eliminar_pregunta" class="btn btn-danger">Eliminar</button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No tienes preguntas publicadas aún.</p>
        <?php endif; ?>

        <a href="../index.php" class="btn-form-pregunta" style="width: 200px;">Volver al inicio</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
