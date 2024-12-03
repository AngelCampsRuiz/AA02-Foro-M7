<?php


$dbHost = 'localhost';
$dbName = 'ForoDB';
$dbUser = 'root';
$dbPass = '';

try {
    // Crear una nueva conexión PDO
    $conexion = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    
   
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>