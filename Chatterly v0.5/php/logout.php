<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatterly";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexion fallida: " . $conn->connect_error);
}

if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la sesión
    $usuario = $_SESSION['usuario'];

    // Actualizar el campo en_linea a 0
    $update_sql = "UPDATE usuarios SET en_linea = 0 WHERE username = '$usuario'";
    $conn->query($update_sql);
    
    // Cerrar la sesión
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header("Location: ../html/index.html");
    echo json_encode(["status" => "success", "message" => "Sesión cerrada correctamente."]);
} else {
    echo json_encode(["status" => "error", "message" => "No hay sesión activa."]);
}

$conn->close();
?>
