<?php
// Archivo: conexion.php

// Configuración de la base de datos
$dbHost = 'localhost';
$dbName = 'ForoDB';
$dbUser = 'Agustin51';
$dbPass = '';

try {
    // Crear una nueva conexión PDO
    $conexion = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    
    // Configurar el modo de error de PDO para que lance excepciones
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conexión exitosa a la base de datos.";
} catch (PDOException $e) {
    // Manejar errores de conexión
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>