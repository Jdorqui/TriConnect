<?php
// Configuración de la base de datos
$host = 'localhost';      // o la dirección de tu servidor MySQL
$dbname = 'chat_app';     // nombre de la base de datos
$username = 'root';       // tu nombre de usuario MySQL
$password = '';           // tu contraseña MySQL

try {
    // Crear una conexión PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Configurar PDO para lanzar excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Manejar errores de conexión
    echo "Conexión fallida: " . $e->getMessage();
    exit;
}
?>
