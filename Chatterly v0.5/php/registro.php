<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatterly";

$conn = new mysqli($servername, $username, $password, $dbname); // Se crea la conexión

if ($conn->connect_error) { // Se verifica la conexión
    echo json_encode(["status" => "error", "message" => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

// Capturar datos del formulario
$email = $_POST['email'];
$alias = $_POST['alias'];
$username = $_POST['username'];
$password = $_POST['password'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$terminos_aceptados = isset($_POST['terminos']) ? 1 : 0;

// Validar que los campos requeridos no estén vacíos
if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "El correo electrónico es obligatorio."]);
    exit();
}
if (empty($alias)) {
    echo json_encode(["status" => "error", "message" => "El nombre de usuario es obligatorio."]);
    exit();
}
if (empty($password)) {
    echo json_encode(["status" => "error", "message" => "La contraseña es obligatoria."]);
    exit();
}
if (empty($fecha_nacimiento)) {
    echo json_encode(["status" => "error", "message" => "La fecha de nacimiento es obligatoria."]);
    exit();
}

// Validar el formato del correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "El formato del correo electrónico no es válido."]);
    exit();
}

// Validar que los términos y condiciones hayan sido aceptados
if ($terminos_aceptados === 0) {
    echo json_encode(["status" => "error", "message" => "Debe aceptar los términos y condiciones."]);
    exit();
}

// Verificar si el alias ya está registrado
$sql = "SELECT * FROM usuarios WHERE alias = '$alias'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "El nombre de usuario ya está registrado."]);
} else {
    // Hashear la contraseña antes de guardarla
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (email, alias, password, username, fecha_nacimiento, terminos_aceptados) 
            VALUES ('$email', '$alias', '$hashed_password', '$username', '$fecha_nacimiento', '$terminos_aceptados')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Registro exitoso."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al registrar: " . $conn->error]);
    }
}

$conn->close();
?>