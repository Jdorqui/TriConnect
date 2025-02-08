<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatterly";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) 
{
    echo json_encode(["status" => "error", "message" => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

$email = $_POST['email'];
$alias = $_POST['alias'];
$username = $_POST['username'];
$password = $_POST['password'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$terminos_aceptados = isset($_POST['terminos']) ? 1 : 0;

// Validar que los campos requeridos no estén vacíos
if (empty($email)) 
{
    echo json_encode(["status" => "error", "message" => "El correo electrónico es obligatorio."]);
    exit();
}
if (empty($alias)) 
{
    echo json_encode(["status" => "error", "message" => "El nombre de usuario es obligatorio."]);
    exit();
}
if (empty($password)) 
{
    echo json_encode(["status" => "error", "message" => "La contraseña es obligatoria."]);
    exit();
}
if (empty($fecha_nacimiento)) 
{
    echo json_encode(["status" => "error", "message" => "La fecha de nacimiento es obligatoria."]);
    exit();
}

// Validar el formato del correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
{
    echo json_encode(["status" => "error", "message" => "El formato del correo electrónico no es válido."]);
    exit();
}

// Validar la contraseña (mínimo 5 caracteres, 1 mayúscula, 1 carácter especial)
if (!preg_match('/^(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{5,}$/', $password)) 
{
    echo json_encode(["status" => "error", "message" => "La contraseña debe tener al menos 5 caracteres, una mayúscula y un carácter especial."]);
    exit();
}

// Validar la fecha de nacimiento (mínimo 18 años y no en el futuro)
$fecha_actual = new DateTime();
$fecha_minima = $fecha_actual->modify('-18 years');
$fecha_nacimiento_dt = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);

if (!$fecha_nacimiento_dt || $fecha_nacimiento_dt > new DateTime() || $fecha_nacimiento_dt > $fecha_minima) 
{
    echo json_encode(["status" => "error", "message" => "Debe tener al menos 18 años y la fecha no puede ser futura."]);
    exit();
}

// Validar que los términos y condiciones hayan sido aceptados
if ($terminos_aceptados === 0) 
{
    echo json_encode(["status" => "error", "message" => "Debe aceptar los términos y condiciones."]);
    exit();
}

// Verificar si el alias ya está registrado
$sql = "SELECT * FROM usuarios WHERE alias = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $alias);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) 
{
    echo json_encode(["status" => "error", "message" => "El nombre de usuario ya está registrado."]);
    exit();
}

// Hashear la contraseña antes de guardarla
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (email, alias, password, username, fecha_nacimiento, terminos_aceptados) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $email, $alias, $hashed_password, $username, $fecha_nacimiento, $terminos_aceptados);

if ($stmt->execute()) 
{
    echo json_encode(["status" => "success", "message" => "Registro exitoso."]);
} 
else 
{
    echo json_encode(["status" => "error", "message" => "Error al registrar: " . $conn->error]);
}

$conn->close();
?>