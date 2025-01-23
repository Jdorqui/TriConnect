<?php
include_once 'logueado.php';

include_once 'config.php';

$user_id = $_SESSION['user_id'];


// Mostrar todos los chats del usuario

$stmt = $conn->prepare("SELECT * FROM chats WHERE user_id = :user_id");

$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

$stmt->execute();

$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar los mensajes del chat seleccionado

if (isset($_GET['chat_id'])) {
    $chat_id = $_GET['chat_id'];

    $stmt = $conn->prepare("SELECT * FROM mensajes WHERE chatId = :chat_id");

    $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);

    $stmt->execute();

    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo' - Mensajes</title>
</head>

<body>
    <header>
        <div class="logo">
            <img src="../img/logoToDo.png" alt="Logo de DeTo'">
        </div>
        <nav>
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="cuenta.php">Mi cuenta</a></li>
                <li><a href="chats.php" class="active">Mensajes</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <div class="chat - window">


        <div class="lista-chats">

            <?php foreach ($chats as $chat): ?>
                <div class="chat">
                   
                        <div class="chat_id"><?= $chat['id'] ?></div>
                        <div class="anuncio_id"><?= $chat['anuncio_id'] ?></div>
                  
                </div>

            <?php endforeach; ?>

        </div>


        <div class="lista-mensajes">
            <?php
            if (isset($_GET['chat_id'])): ?>
                <?php foreach ($mensajes as $mensaje): ?>

                    <div class="mensaje">

                        <div class="texto"><?= $mensaje['mensaje'] ?></div>
                        <div class="fecha"><?= $mensaje['fecha_envio'] ?></div>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>
                <p>Selecciona un chat.</p>

            <?php endif; ?>

        </div>


    </div>




    <footer>
        <p>&copy; 2025 DeTo'</p>
    </footer>




</body>

</html>