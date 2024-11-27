<?php
session_start();
include('../BDD/conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    echo '<div class="alert alert-danger text-center">Debes iniciar sesión para enviar preguntas.</div>';
    echo '<a href="../login.php" class="btn btn-primary d-block mx-auto" style="width: 200px;">Iniciar Sesión</a>';
    exit(); // Evitar mostrar el formulario si no está autenticado
}

// Procesar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $pregunta = trim($_POST['pregunta']);
    $usuario_id = $_SESSION['usuario_id']; // Obtener el ID del usuario de la sesión

    try {
        // Insertar la pregunta en la base de datos
        $sql = "INSERT INTO preguntas (titulo, descripcion, usuario_id) VALUES (:titulo, :descripcion, :usuario_id)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $pregunta, PDO::PARAM_STR);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success text-center">Tu pregunta ha sido publicada exitosamente.</div>';
        } else {
            echo '<div class="alert alert-danger text-center">Error al publicar la pregunta. Inténtalo nuevamente.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger text-center">Error en la base de datos: ' . $e->getMessage() . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Preguntas</title>
    <link rel="stylesheet" href="./../Styles/styles.css">
    <script src="./Js/valida.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Formulario de Preguntas</h1>
        <form action="form_preguntas.php" method="POST">
            <div class="mb-3">
                <label for="titulo" class="form-label">Introduce un título:</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
                <span id="error-titulo" style="color: red;"></span> 
            </div>
            <div class="mb-3">
                <label for="pregunta" class="form-label">Introduce una pregunta:</label>
                <textarea id="pregunta" name="pregunta" rows="4" class="form-control" required></textarea>
                <span id="error-pregunta" style="color: red;"></span> 
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <a href="../index.php" class="btn btn-primary d-block mx-auto" style="width: 200px;">Volver al inicio</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>