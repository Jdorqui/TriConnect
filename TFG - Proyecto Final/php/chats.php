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
            SELECT mensajes.*, usuarios.alias AS sender_alias
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes en Tiempo Real</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Chat</h1>

    <div>
        <h2>Mensajes</h2>
        <div id="lista-mensajes" style="border: 1px solid #ccc; padding: 10px; height: 300px; overflow-y: auto;">
            <!-- Los mensajes se cargarán aquí -->
        </div>
        <form id="form-mensaje">
            <textarea id="mensaje" placeholder="Escribe tu mensaje..." required></textarea>
            <button type="submit">Enviar</button>
        </form>
    </div>

    <script>
        const chatId = <?= json_encode($_GET['chat_id']) ?>;
        const userId = <?= json_encode($_SESSION['user_id']) ?>;

        // Cargar mensajes en tiempo real
        function cargarMensajes() {
            $.get('obtener_mensajes.php', { chat_id: chatId }, function (mensajes) {
                const listaMensajes = $('#lista-mensajes');
                listaMensajes.empty();

                mensajes.forEach(mensaje => {
                    const esPropio = mensaje.sender_id === userId;
                    const clase = esPropio ? 'mensaje-propio' : 'mensaje-otro';

                    listaMensajes.append(`
                        <div class="${clase}">
                            <strong>${mensaje.sender_alias}:</strong>
                            ${mensaje.mensaje}
                            <small>${mensaje.fecha_envio}</small>
                        </div>
                    `);
                });

                // Desplazar hacia el final
                listaMensajes.scrollTop(listaMensajes[0].scrollHeight);
            });
        }

        // Enviar mensaje
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

        // Actualizar mensajes cada 2 segundos
        setInterval(cargarMensajes, 2000);

        // Cargar mensajes al inicio
        cargarMensajes();
    </script>
</body>
</html>
