<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chat";

error_reporting(error_level: E_ERROR | E_PARSE);
ini_set(option: 'display_errors', value: 1);

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Registro de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $email, $password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();
}

// Autenticación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo json_encode(["status" => "success", "user_id" => $user['id']]);
        } else {
            echo json_encode(["status" => "error", "message" => "Contraseña incorrecta"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
    }
    $stmt->close();
}

// Enviar mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'sendMessage') {
    $remitente_id = $_POST['remitente_id'];
    $destinatario_id = $_POST['destinatario_id'];
    $mensaje = $_POST['mensaje'];

    $sql = "INSERT INTO mensajes (remitente_id, destinatario_id, mensaje) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $remitente_id, $destinatario_id, $mensaje);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();
}

// Obtener mensajes
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getMessages') {
    $remitente_id = $_GET['remitente_id'];
    $destinatario_id = $_GET['destinatario_id'];

    $sql = "SELECT remitente_id, destinatario_id, mensaje, fecha 
            FROM mensajes 
            WHERE (remitente_id = ? AND destinatario_id = ?) 
               OR (remitente_id = ? AND destinatario_id = ?) 
            ORDER BY fecha ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $remitente_id, $destinatario_id, $destinatario_id, $remitente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $mensajes = [];
    while ($row = $result->fetch_assoc()) {
        $mensajes[] = $row;
    }

    echo json_encode($mensajes);
    $stmt->close();
}

$conn->close();
?>
