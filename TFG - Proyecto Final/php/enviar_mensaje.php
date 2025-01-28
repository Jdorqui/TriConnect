<?php
include_once 'logueado.php';
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chat_id = filter_input(INPUT_POST, 'chat_id', FILTER_VALIDATE_INT);
    $mensaje = trim(filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING));

    if ($chat_id && $mensaje) {
        $stmt = $conn->prepare("
            SELECT anuncios.user_id AS anuncio_owner, chats.user_id AS comprador
            FROM chats
            JOIN anuncios ON chats.anuncio_id = anuncios.id
            WHERE chats.id = :chat_id
        ");
        $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && ($result['anuncio_owner'] == $_SESSION['user_id'] || $result['comprador'] == $_SESSION['user_id'])) {
            $receiver_id = ($result['anuncio_owner'] == $_SESSION['user_id']) ? $result['comprador'] : $result['anuncio_owner'];

            $stmt = $conn->prepare("
            INSERT INTO mensajes (chatId, sender_id, receiver_id, mensaje, enviado, entregado, leido)
            VALUES (:chat_id, :sender_id, :receiver_id, :mensaje, TRUE, FALSE, FALSE)
        ");
        $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
        $stmt->bindParam(':sender_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
        $stmt->execute();
        

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
    }
}

http_response_code(400);
echo json_encode(['error' => 'No se pudo enviar el mensaje']);
