<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Preguntas</title>
    <script src="../Js/valida.js" defer></script>
    <link rel="stylesheet" href="../Styles/estilos.css">
</head>
<body>
    <form action="form_preguntas.php" method="POST">  
        <label for="titulo">Introduce un título:</label>
        <input type="text" id="titulo" name="titulo">
        <input type="submit" value="Enviar" id="btn-enviar">
        <br>
        <span id="error-titulo" style="color: red;"></span>     
        <br><br>
        <label for="pregunta">Introduce una pregunta:</label>
        <br>
        <textarea id="pregunta" name="pregunta" rows="4" cols="50"></textarea>
        <br>
        <span id="error-pregunta" style="color: red;"></span> 
    </form>
</body>
</html>
