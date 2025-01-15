<?php
session_start();
include 'db/conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener las conversaciones del usuario
$stmt = $conn->prepare("SELECT u.id, u.alias FROM usuarios u 
                        JOIN mensajes m ON m.sender_id = u.id OR m.receiver_id = u.id
                        WHERE u.id != :user_id GROUP BY u.id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener los mensajes de una conversación
if (isset($_GET['conversation_id'])) {
    $conversation_id = $_GET['conversation_id'];

    // Obtener mensajes entre el usuario y la otra persona
    $stmt = $conn->prepare("SELECT m.mensaje, m.fecha_envio, u1.alias AS sender, u2.alias AS receiver
                            FROM mensajes m
                            JOIN usuarios u1 ON m.sender_id = u1.id
                            JOIN usuarios u2 ON m.receiver_id = u2.id
                            WHERE (m.sender_id = :user_id AND m.receiver_id = :conversation_id) OR 
                                  (m.sender_id = :conversation_id AND m.receiver_id = :user_id)
                            ORDER BY m.fecha_envio");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':conversation_id', $conversation_id);
    $stmt->execute();

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $receiver_id = $_POST['receiver_id'];

    $stmt = $conn->prepare("INSERT INTO mensajes (sender_id, receiver_id, mensaje) 
                            VALUES (:sender_id, :receiver_id, :mensaje)");
    $stmt->bindParam(':sender_id', $user_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->bindParam(':mensaje', $message);
    $stmt->execute();

    header("Location: mensajes.php?conversation_id=" . $receiver_id); // Redirigir a la conversación
}
?>

<!-- HTML para mostrar las conversaciones y mensajes -->
<h1>Conversaciones</h1>
<ul>
    <?php foreach ($conversations as $conversation): ?>
        <li><a href="mensajes.php?conversation_id=<?= $conversation['id'] ?>"><?= $conversation['alias'] ?></a></li>
    <?php endforeach; ?>
</ul>

<?php if (isset($messages)): ?>
    <h2>Conversación con <?= $messages[0]['receiver'] ?></h2>
    <div>
        <?php foreach ($messages as $msg): ?>
            <p><strong><?= $msg['sender'] ?>:</strong> <?= $msg['mensaje'] ?> <em>(<?= $msg['fecha_envio'] ?>)</em></p>
        <?php endforeach; ?>
    </div>

    <form method="POST">
        <textarea name="message" placeholder="Escribe tu mensaje..."></textarea>
        <input type="hidden" name="receiver_id" value="<?= $conversation_id ?>">
        <button type="submit">Enviar</button>
    </form>
<?php endif; ?>
