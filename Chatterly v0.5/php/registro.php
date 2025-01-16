<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatterly";

$conn = new mysqli($servername, $username, $password, $dbname); //se crea la conexion

if ($conn->connect_error) //se verifica la conexion
{
    echo json_encode(["status" => "error", "message" => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

$email = $_POST['email'];
$alias = $_POST['alias'];
$username = $_POST['username'];
$password = $_POST['password'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];

$sql = "SELECT * FROM usuarios WHERE alias = '$alias'"; //verifica si el alias ya existe
$result = $conn->query($sql); 

if ($result->num_rows > 0) 
{
    echo json_encode(["status" => "error", "message" => "El nombre de usuario ya está registrado."]);
} 
else 
{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); //hashear la contraseña antes de guardarla
    
    $sql = "INSERT INTO usuarios (email, alias, password, username, fecha_nacimiento) VALUES ('$email', '$alias', '$hashed_password', '$username', '$fecha_nacimiento')";
    if ($conn->query($sql) === TRUE) 
    {
        echo json_encode(["status" => "success", "message" => "Registro exitoso."]);
    }
    else 
    {
        echo json_encode(["status" => "error", "message" => "Error al registrar: " . $conn->error]);
    }
}

$conn->close();
?>
