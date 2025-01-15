<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$host = 'localhost';
$db = 'chat_app';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['user_id'])) {
    $receiver_id = $_GET['user_id'];

    // Obtener los mensajes de la conversación entre el usuario y el receptor
    $stmt = $conn->prepare("SELECT m.mensaje, m.fecha_envio, u1.email AS sender, u2.email AS receiver
                            FROM mensajes m
                            JOIN usuarios u1 ON m.sender_id = u1.id
                            JOIN usuarios u2 ON m.receiver_id = u2.id
                            WHERE (m.sender_id = :user_id AND m.receiver_id = :receiver_id) OR 
                                  (m.sender_id = :receiver_id AND m.receiver_id = :user_id)
                            ORDER BY m.fecha_envio ASC");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
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
}

if (isset($_GET['action']) && $_GET['action'] == 'get_messages') {
    // Obtener los mensajes más recientes sin recargar la página
    $stmt = $conn->prepare("SELECT m.mensaje, m.fecha_envio, u1.email AS sender, u2.email AS receiver
                            FROM mensajes m
                            JOIN usuarios u1 ON m.sender_id = u1.id
                            JOIN usuarios u2 ON m.receiver_id = u2.id
                            WHERE (m.sender_id = :user_id AND m.receiver_id = :receiver_id) OR 
                                  (m.sender_id = :receiver_id AND m.receiver_id = :user_id)
                            ORDER BY m.fecha_envio ASC");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($messages);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat en Directo</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Bienvenido al Chat</h1>
    <h2>Usuarios disponibles para chatear</h2>
    <ul>
        <?php 
        // Mostrar la lista de usuarios con los que puedes chatear
        $stmt = $conn->prepare("SELECT id, email FROM usuarios WHERE id != :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user): ?>
            <li><a href="chat.php?user_id=<?= $user['id'] ?>"><?= $user['email'] ?></a></li>
        <?php endforeach; ?>
    </ul>

    <?php if (isset($messages)): ?>
        <h2>Conversación con: <?= $messages[0]['receiver'] ?></h2>
        <div id="message-list" class="chat-window">
            <?php foreach ($messages as $msg): ?>
                <div class="message <?php echo ($msg['sender'] == $_SESSION['email']) ? 'sent' : 'received'; ?>">
                    <div class="sender"><?= $msg['sender'] ?> <span class="timestamp"><?= $msg['fecha_envio'] ?></span></div>
                    <div class="text"><?= $msg['mensaje'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <form id="message-form" method="POST">
            <textarea name="message" placeholder="Escribe tu mensaje..."></textarea>
            <input type="hidden" name="receiver_id" value="<?= $receiver_id ?>">
            <button type="submit">Enviar</button>
        </form>

        <!-- Botón para bajar el chat manualmente -->
        <button id="scroll-to-bottom" style="display: none;">↓</button>

        <script>
            // Función para desplazar automáticamente el chat hacia abajo
            function scrollToBottom() {
                var messageList = document.getElementById('message-list');
                messageList.scrollTop = messageList.scrollHeight;
            }

            // Cada 2 segundos, obtener nuevos mensajes sin afectar el scroll
            setInterval(function() {
                $.ajax({
                    url: 'chat.php?user_id=<?= $receiver_id ?>&action=get_messages',
                    method: 'GET',
                    success: function(response) {
                        var messages = JSON.parse(response);
                        var messageList = $('#message-list');
                        messageList.empty(); // Limpiar la lista de mensajes
                        messages.forEach(function(msg) {
                            var messageDiv = $('<div class="message ' + (msg.sender == '<?= $_SESSION['email'] ?>' ? 'sent' : 'received') + '"></div>');
                            messageDiv.append('<div class="sender">' + msg.sender + ' <span class="timestamp">' + msg.fecha_envio + '</span></div>');
                            messageDiv.append('<div class="text">' + msg.mensaje + '</div>');
                            messageList.append(messageDiv);
                        });

                        // Mostrar el botón si no está al fondo
                        var isAtBottom = messageList.scrollHeight - messageList.scrollTop === messageList.clientHeight;
                        if (!isAtBottom) {
                            $('#scroll-to-bottom').show();
                        }
                    }
                });
            }, 200);

            $('#message-form').on('submit', function(e) {
                e.preventDefault();
                var message = $('textarea[name="message"]').val();
                var receiver_id = $('input[name="receiver_id"]').val();
                
                $.post('chat.php', { message: message, receiver_id: receiver_id }, function() {
                    // Después de enviar, limpiar el campo de texto
                    $('textarea[name="message"]').val('');
                    // Desplazar hacia el último mensaje automáticamente
                    scrollToBottom();
                    // Ocultar el botón si el usuario ya está al fondo
                    $('#scroll-to-bottom').hide();
                });
            });

            $('#scroll-to-bottom').on('click', function() {
                scrollToBottom();
                $(this).hide(); // Ocultar el botón después de hacer clic
            });

            // Al cargar la página, asegurarse de que el chat está al final
            $(document).ready(function() {
                scrollToBottom();
            });
        </script>
    <?php endif; ?>
</body>
</html>
