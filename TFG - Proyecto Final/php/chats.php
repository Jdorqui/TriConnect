<?php 
include_once 'logueado.php';
include_once 'config.php';

$user_id = $_SESSION['user_id'];

// Si se recibe un anuncio ID (anun_id), verificar o crear el chat
if (isset($_GET['anun_id'])) {
    $anuncio_id = filter_input(INPUT_GET, 'anun_id', FILTER_VALIDATE_INT);

    if ($anuncio_id) {
        // Verificar si el chat ya existe
        $stmt = $conn->prepare("
            SELECT id FROM chats
            WHERE user_id = :user_id AND anuncio_id = :anuncio_id
        ");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':anuncio_id', $anuncio_id, PDO::PARAM_INT);
        $stmt->execute();
        $chat = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$chat) {
            // Crear un nuevo chat si no existe
            $stmt = $conn->prepare("
                INSERT INTO chats (user_id, anuncio_id)
                VALUES (:user_id, :anuncio_id)
            ");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':anuncio_id', $anuncio_id, PDO::PARAM_INT);
            $stmt->execute();

            // Obtener el ID del chat recién creado
            $chat_id = $conn->lastInsertId();
        } else {
            // Usar el ID del chat existente
            $chat_id = $chat['id'];
        }

        // Redirigir al chat
        header("Location: chats.php?chat_id=$chat_id");
        exit;
    } else {
        echo "ID de anuncio no válido.";
        exit;
    }
}

// Obtener todos los chats del usuario
$stmt = $conn->prepare("
    SELECT chats.id, anuncios.titulo, anuncios.user_id AS anuncio_owner
    FROM chats
    JOIN anuncios ON chats.anuncio_id = anuncios.id
    WHERE chats.user_id = :user_id OR anuncios.user_id = :user_id
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener mensajes del chat seleccionado
$mensajes = [];
$is_anuncio_owner = false;

if (isset($_GET['chat_id'])) {
    $chat_id = filter_input(INPUT_GET, 'chat_id', FILTER_VALIDATE_INT);

    if ($chat_id) {
        // Verificar si el usuario es dueño del anuncio
        $stmt = $conn->prepare("
            SELECT anuncios.user_id AS anuncio_owner
            FROM chats
            JOIN anuncios ON chats.anuncio_id = anuncios.id
            WHERE chats.id = :chat_id
        ");
        $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['anuncio_owner'] == $user_id) {
            $is_anuncio_owner = true;
        }

        // Obtener los mensajes del chat
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
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Conversaciones</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/chats.css">
</head>
<body>
    <div id="lista-chats">
        <h2>Chats Disponibles</h2>
        <ul>
            <?php foreach ($chats as $chat): ?>
                <li>
                    <a href="chats.php?chat_id=<?= $chat['id'] ?>">
                        <?= htmlspecialchars($chat['titulo']) ?>
                        (<?= $chat['anuncio_owner'] == $user_id ? 'Dueño' : 'Comprador' ?>)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if (isset($_GET['chat_id'])): ?>
    <div id="chat-container">
        <h2>Mensajes</h2>
        <div id="lista-mensajes">
            <?php foreach ($mensajes as $mensaje): ?>
                <div class="<?= $mensaje['sender_id'] == $user_id ? 'mensaje-propio' : 'mensaje-otro' ?>">
                    <strong><?= htmlspecialchars($mensaje['sender_nombre']) ?>:</strong>
                    <?= htmlspecialchars($mensaje['mensaje']) ?>
                    <small><?= $mensaje['fecha_envio'] ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <form id="form-mensaje">
            <textarea id="mensaje" placeholder="Escribe tu mensaje..." required></textarea>
            <button type="submit">Enviar</button>
        </form>
    </div>
    <?php endif; ?>

    <script>
        const chatId = <?= json_encode($_GET['chat_id']) ?>;
        const userId = <?= json_encode($_SESSION['user_id']) ?>;

        function cargarMensajes() {
            $.get('obtener_mensajes.php', { chat_id: chatId }, function (mensajes) { // Crear obtener_mensajes.php con el código de abajo
                const listaMensajes = $('#lista-mensajes'); // Obtener el contenedor de mensajes con jQuery
                listaMensajes.empty(); // Limpiar los mensajes actuales

                mensajes.forEach(mensaje => { // Iterar sobre los mensajes recibidos con forEach para
                    const esPropio = mensaje.sender_id === userId;
                    const clase = esPropio ? 'mensaje-propio' : 'mensaje-otro';

                    listaMensajes.append(`
                        <div class="${clase}">
                            <strong>${mensaje.sender_nombre}:</strong>
                            ${mensaje.mensaje}
                            <small>${mensaje.fecha_envio}</small>
                        </div>
                    `);
                });

                listaMensajes.scrollTop(listaMensajes[0].scrollHeight);
            });
        }

        $('#form-mensaje').submit(function (e) {
            e.preventDefault();

            const mensaje = $('#mensaje').val();
 
            $.post('enviar_mensaje.php', { chat_id: chatId, mensaje }, function (response) {
                if (response.success) {
                    $('#mensaje').val('');
                    cargarMensajes();
                } else {
                    alert('No se pudo enviar el mensaje');
                }
            }, 'json');
        });

        setInterval(cargarMensajes, 2000);
        cargarMensajes();
    </script>
</body>
</html>