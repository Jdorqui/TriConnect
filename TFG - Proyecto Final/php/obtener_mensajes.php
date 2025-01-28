<?php
include_once 'logueado.php';
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $chat_id = filter_input(INPUT_GET, 'chat_id', FILTER_VALIDATE_INT);

    if ($chat_id) {
        $stmt = $conn->prepare("
            SELECT mensajes.*, usuarios.nombre AS sender_nombre
            FROM mensajes
            JOIN usuarios ON mensajes.sender_id = usuarios.id
            WHERE mensajes.chatId = :chat_id
            ORDER BY mensajes.fecha_envio ASC
        ");
        $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
        $stmt->execute();
        $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($mensajes);
        exit;
    }
}

http_response_code(400);
echo json_encode(['error' => 'Chat ID no válido']);
