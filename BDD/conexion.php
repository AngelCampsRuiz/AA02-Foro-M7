<?php

// Configuración de la base de datos
$dbHost = 'localhost';
$dbName = 'ForoDB';
$dbUser = 'root';
$dbPass = 'Agustin51';

try {
    // Crear una nueva conexión PDO
    $conexion = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    
    // Configurar el modo de error de PDO para que lance excepciones
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Manejar errores de conexión
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>