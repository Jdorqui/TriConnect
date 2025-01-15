<?php
// error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alias = trim($_POST['alias']);
    $password = trim($_POST['password']);
}

$servername = "localhost";
$username = "root";
$pass = "root";
$dbname = "practica3";

try {
    $conn = new mysqli($servername, $username, $pass, $dbname);
} catch (Exception $e) {
    echo "ERROR-000: Conexión fallida con la base de datos. " . $e->getMessage();

    die(0);
}

$check_user_query = $conn->query("SELECT 1 FROM usuario WHERE alias = '$alias'");

if ($check_user_query->num_rows == 0) {
    echo "ERROR-001: El usuario '$alias' no existe.";

    die(1);
}

$check_password_query = $conn->query("SELECT password FROM usuario WHERE alias = '$alias'");
$row = $check_password_query->fetch_assoc();
if (password_verify($password, $row['password'])) {
    session_start();

    $_SESSION["alias"] = $alias;
    $_SESSION["password"] = $password;

    echo "SUCCESS";
} else {
    echo "ERROR-002: La contraseña del usuario '$alias' no es correcta.";

    die(2);
}

$conn->close();