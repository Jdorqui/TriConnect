<?php

include_once 'logueado.php';
include_once 'config.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM anuncios WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si el usuario que está viendo la información es el dueño del anuncio
$usuario_id = $_SESSION['user_id'];

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anuncios - ToDo'</title>

    <link rel="stylesheet" href="../css/anuncio_info.css">
</head>

<body>

    <h1>Información - Anuncio</h1>

    <div id="anuncios-atributos" class="anuncios-atributos">
        <?php foreach ($anuncios as $anun): ?>
            <div class="anuncio">
                <div class="titulo"><?= htmlspecialchars($anun['titulo']) ?></div>
                <div class="descripcion"><?= htmlspecialchars($anun['descripcion']) ?></div>
                <div class="categoria"><?= htmlspecialchars($anun['categoria']) ?></div>
                <div class="precio"><?= htmlspecialchars($anun['precio']) ?></div>
                <div class="estado"><?= htmlspecialchars($anun['estado']) ?></div>
            </div>

            <!-- Verificar si el usuario es el propietario del anuncio -->
            <?php if ($anun['user_id'] !== $usuario_id): ?>
                <!-- Mostrar el enlace al chat solo si el usuario no es el propietario -->
                <a href="chats.php?anun_id=<?= $anun['id'] ?>">Chat</a>
            <?php else: ?>
                <!-- Opcionalmente, podrías mostrar un mensaje si es el dueño del anuncio -->
                <p>No puedes iniciar un chat con tu propio anuncio.</p>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>

</body>

</html>
