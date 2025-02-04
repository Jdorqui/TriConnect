<?php
session_start();

// Conexión a la base de datos
$host = 'localhost';
$dbname = 'chatterly';
$db_user = 'root';
$db_pass = '';

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(["error" => "Error de conexión: " . $e->getMessage()]));
}

// Validación de la sesión de usuario
if (!isset($_SESSION['usuario'])) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Obtener ID del usuario
$stmt = $pdo->prepare("SELECT id_user FROM usuarios WHERE username = ?");
$stmt->execute([$_SESSION['usuario']]);
$usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuarioData) {
    die(json_encode(["error" => "Usuario no encontrado"]));
}
$id_usuario = $usuarioData['id_user'];

// Recibir datos
$room = isset($_REQUEST['room']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_REQUEST['room']) : '';
$userType = isset($_REQUEST['user']) ? $_REQUEST['user'] : 'caller';
$partnerType = ($userType === 'caller') ? 'callee' : 'caller';

// Aceptar llamada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'accept_call') {
    $stmt = $pdo->prepare("UPDATE llamadas SET user_callee_id = ?, estado = 'aceptada' WHERE room_id = ? AND estado = 'pendiente'");
    $stmt->execute([$id_usuario, $room]);
    echo json_encode(["status" => "call_accepted"]);
    exit();
}

// Procesar la solicitud de POST (enviar señales)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['type']) || !in_array($data['type'], ['offer', 'answer', 'candidate', 'reject'])) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(['error' => 'Tipo de mensaje inválido']);
        exit();
    }

    // Comprobar si la sala ya existe
    $stmt = $pdo->prepare("SELECT id_llamada FROM llamadas WHERE room_id = ?");
    $stmt->execute([$room]);
    $existingCall = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existingCall) {
        // Si no existe, se crea una nueva llamada
        $stmt = $pdo->prepare("INSERT INTO llamadas (room_id, user_caller_id, estado) VALUES (?, ?, ?)");
        $stmt->execute([$room, $id_usuario, 'pendiente']);
    }
    echo json_encode(["status" => "ok"]);
    exit;
}

// Método GET: recuperar llamadas pendientes
$stmt = $pdo->prepare("SELECT id_llamada, room_id, user_caller_id, user_callee_id, fecha, estado FROM llamadas WHERE room_id = ?");
$stmt->execute([$room]);
$calls = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($calls);
exit;
