<?php
session_start();
// Conexión a la base de datos (ajusta estos parámetros según tu configuración)
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
    die("Error de conexión: " . $e->getMessage());
}

// Validación de la sesión de usuario
if (!isset($_SESSION['usuario'])) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Se reciben los parámetros 'room' y 'user' (por ejemplo, 'caller' o 'callee')
$room = isset($_REQUEST['room']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_REQUEST['room']) : 'default';
$user = isset($_REQUEST['user']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_REQUEST['user']) : 'caller';

// Aquí determinamos el usuario opuesto para hacer la llamada
$partner = ($user === 'caller') ? 'callee' : 'caller';

// Procesar la solicitud de POST (enviar señales)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer la entrada JSON
    $data = file_get_contents('php://input');
    $message = json_decode($data, true);

    // Validar el formato del mensaje
    if (isset($message['type']) && in_array($message['type'], ['offer', 'answer', 'candidate', 'reject'])) {
        $event_type = $message['type'];

        // Insertar la llamada en la base de datos (sin 'message', ya que no existe)
        $stmt = $pdo->prepare("INSERT INTO llamadas (room_id, user_caller_id, user_callee_id, estado) 
                               VALUES (?, ?, ?, ?)");
        // Asumimos que el 'user' es el emisor y el 'partner' es el receptor
        $stmt->execute([$room, $user, $partner, 'pendiente']);

        echo json_encode(["status" => "ok"]);
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(['error' => 'Tipo de mensaje inválido o incompleto']);
    }
    exit;
} else {
    // Método GET: se devuelven las llamadas de la sala con los usuarios correspondientes
    $stmt = $pdo->prepare("SELECT id_llamada, room_id, user_caller_id, user_callee_id, fecha, estado 
                           FROM llamadas 
                           WHERE room_id = :room AND (user_caller_id = :user OR user_callee_id = :user)");
    $stmt->execute(['room' => $room, 'user' => $user]);
    $calls = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Eliminamos las llamadas procesadas para evitar reenvíos
    if (!empty($calls)) {
        $ids = array_column($calls, 'id_llamada');
        $in = implode(',', array_map('intval', $ids));
        $pdo->exec("DELETE FROM llamadas WHERE id_llamada IN ($in)");
    }

    echo json_encode($calls);
    exit;
}

?>
