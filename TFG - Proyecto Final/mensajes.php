<?php
session_start();


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

// Obtener lista de usuarios
$stmt = $conn->prepare("SELECT id, email FROM usuarios WHERE id != :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener mensajes si se seleccionó un usuario
if (isset($_GET['user_id'])) {
    $receiver_id = $_GET['user_id'];

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat en Directo</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: #2a3942;
        }

        .container {
            display: flex;
            flex: 1;
        }

        .sidebar-menu {
            width: 70px;
            background-color: #111b21;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 0;
        }

        .menu-item {
            margin: 20px 0;
            color: white;
            cursor: pointer;
            text-align: center;
            transition: color 0.3s;
        }

        .menu-item:hover {
            color: #00a884;
        }

        .menu-item img {
            width: 30px;
            height: 30px;
        }

        .profile-pic {
            margin-top: auto;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
            cursor: pointer;
        }

        .chat-list {
            width: 30%;
            background-color: #111b21;
            color: white;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #333;
        }

        .search-bar {
            padding: 10px;
            border-bottom: 1px solid #333;
        }

        .search-bar input {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 5px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .chat-item {
            display: flex;
            align-items: center;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .chat-item:hover {
            background-color: #202c33;
        }

        .chat-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .chat-info {
            flex: 1;
        }

        .chat-info .name {
            font-weight: bold;
            display: block;
        }

        .chat-window {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #0b141a;
            color: white;
        }

        .chat-header {
            padding: 10px;
            background-color: #202c33;
            display: flex;
            align-items: center;
        }

        .chat-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .message-list {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
        }

        .message {
            margin: 5px 0;
        }

        .message.sent {
            text-align: right;
        }

        .message .text {
            display: inline-block;
            padding: 10px;
            border-radius: 5px;
        }

        .message.sent .text {
            background-color: #005c4b;
            color: white;
        }

        .message.received .text {
            background-color: #202c33;
            color: white;
        }

        .message-input {
            display: flex;
            padding: 10px;
            background-color: #202c33;
            border-top: 1px solid #333;
        }

        .message-input textarea {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            resize: none;
        }

        .message-input button {
            padding: 10px;
            border: none;
            background-color: #005c4b;
            color: white;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .no-chat-selected {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Menú de navegación lateral -->
    <div class="sidebar-menu">
        <div class="menu-item">
            <img src="icon-home.png" alt="Home">
        </div>
        <div class="menu-item">
            <img src="icon-chat.png" alt="Chat">
        </div>
        <div class="menu-item">
            <img src="icon-users.png" alt="Usuarios">
        </div>
        <div class="menu-item">
            <img src="icon-settings.png" alt="Configuración">
        </div>
        <img src="profile-pic.png" alt="Profile" class="profile-pic">
    </div>

    <div class="container">
        <!-- Panel izquierdo: Lista de chats -->
        <div class="chat-list">
            <div class="search-bar">
                <input type="text" placeholder="Buscar chats...">
            </div>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li class="chat-item">
                        <a href="chat.php?user_id=<?= $user['id'] ?>">
                            <img src="default-avatar.png" alt="Avatar">
                            <div class="chat-info">
                                <span class="name"><?= $user['email'] ?></span>
                                <span class="last-message">Último mensaje...</span>
                            </div>
                            <span class="time">Hora</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Panel derecho: Ventana de chat -->
        <div class="chat-window">
            <?php if (isset($messages)): ?>
                <!-- Encabezado del chat -->
                <div class="chat-header">
                    <img src="default-avatar.png" alt="Avatar">
                    <span class="chat-title">Chat con <?= $messages[0]['receiver'] ?></span>
                </div>

                <!-- Lista de mensajes -->
                <div id="message-list" class="message-list">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message <?php echo ($msg['sender'] == $_SESSION['email']) ? 'sent' : 'received'; ?>">
                            <div class="text"><?= $msg['mensaje'] ?></div>
                            <div class="timestamp"><?= $msg['fecha_envio'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Barra de entrada -->
                <form id="message-form" class="message-input" method="POST">
                    <textarea name="message" placeholder="Escribe tu mensaje..."></textarea>
                    <input type="hidden" name="receiver_id" value="<?= $receiver_id ?>">
                    <button type="submit">Enviar</button>
                </form>
            <?php else: ?>
                <div class="no-chat-selected">
                    <p>Selecciona un chat para comenzar a chatear</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
