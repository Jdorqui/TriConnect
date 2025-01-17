<?php
session_start();
include 'config.php';




$user_id = $_SESSION['user_id'];

// Obtener las conversaciones del usuario
$stmt = $conn->prepare("
    SELECT DISTINCT u.id, u.alias
    FROM usuarios u
    JOIN mensajes m ON (m.sender_id = u.id OR m.receiver_id = u.id)
    WHERE u.id != :user_id
    ORDER BY u.alias ASC
");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener los mensajes de una conversación
$messages = [];
$current_contact_name = '';
if (isset($_GET['conversation_id'])) {
    $conversation_id = $_GET['conversation_id'];

    // Obtener el alias del contacto
    $stmt = $conn->prepare("SELECT alias FROM usuarios WHERE id = :conversation_id");
    $stmt->bindParam(':conversation_id', $conversation_id);
    $stmt->execute();
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_contact_name = $contact ? $contact['alias'] : '';

    // Obtener mensajes entre el usuario y el contacto
    $stmt = $conn->prepare("
        SELECT m.mensaje, m.fecha_envio, 
               CASE WHEN m.sender_id = :user_id THEN 'Yo' ELSE u1.alias END AS sender
        FROM mensajes m
        JOIN usuarios u1 ON m.sender_id = u1.id
        WHERE (m.sender_id = :user_id AND m.receiver_id = :conversation_id)
           OR (m.sender_id = :conversation_id AND m.receiver_id = :user_id)
        ORDER BY m.fecha_envio
    ");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':conversation_id', $conversation_id);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Enviar un nuevo mensaje
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    $receiver_id = $_POST['receiver_id'];

    if (!empty($message)) {
        $stmt = $conn->prepare("
            INSERT INTO mensajes (sender_id, receiver_id, mensaje) 
            VALUES (:sender_id, :receiver_id, :mensaje)
        ");
        $stmt->bindParam(':sender_id', $user_id);
        $stmt->bindParam(':receiver_id', $receiver_id);
        $stmt->bindParam(':mensaje', $message);
        $stmt->execute();

        header("Location: mensajes.php?conversation_id=" . $receiver_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Conversaciones</h1>
    <ul>
        <?php foreach ($conversations as $conversation): ?>
            <li>
                <a href="mensajes.php?conversation_id=<?= htmlspecialchars($conversation['id']) ?>">
                    <?= htmlspecialchars($conversation['alias']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if (isset($_GET['conversation_id'])): ?>
        <h2>Conversación con <?= htmlspecialchars($current_contact_name) ?></h2>
        <div>
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                    <p>
                        <strong><?= htmlspecialchars($msg['sender']) ?>:</strong> 
                        <?= htmlspecialchars($msg['mensaje']) ?> 
                        <em>(<?= htmlspecialchars($msg['fecha_envio']) ?>)</em>
                    </p>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay mensajes en esta conversación.</p>
            <?php endif; ?>
        </div>

        <form method="POST">
            <textarea name="message" placeholder="Escribe tu mensaje..." required></textarea>
            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($conversation_id) ?>">
            <button type="submit">Enviar</button>
        </form>
    <?php endif; ?>
</body>
</html>
