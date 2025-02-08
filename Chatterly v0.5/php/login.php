<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatterly";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) 
{
    die("Conexion fallida: " . $conn->connect_error);
}

session_start();

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

$usuario = $conn->real_escape_string($usuario); //previene inyecciones de sql

$sql = "SELECT * FROM usuarios WHERE username = '$usuario'"; //busca el usuario en la base de datos
$result = $conn->query($sql);

if ($result->num_rows > 0) 
{
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) 
    {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['password'] = $password;

        // Marcar al usuario como en línea
        $update_sql = "UPDATE usuarios SET en_linea = 1 WHERE username = '$usuario'";
        $conn->query($update_sql);

        echo json_encode(["status" => "success", "message" => "Inicio de sesión exitoso."]);
    } 
    else 
    {
        echo json_encode(["status" => "error", "message" => "Usuario o contraseña no válidos."]);
    }
} 
else 
{
    echo json_encode(["status" => "error", "message" => "Usuario o contraseña no válidos."]);
}

$conn->close();
?>