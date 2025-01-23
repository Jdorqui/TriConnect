<?php

include_once 'logueado.php';
include_once 'config.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM anuncios WHERE id = :id");


$stmt->bindParam(':id', $id, PDO::PARAM_INT);

$stmt->execute();

$anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anuncios - ToDo'</title>

    <link rel="stylesheet" href="../css/anuncio_info.css">
</head>

<body>


    <h1>Anuncios</h1>

    <div id="anuncios-atributos" class="anuncios-atributos">
        <?php foreach ($anuncios as $anun): ?>
            <div class="anuncio">
                <div class="titulo"><?= $anun['titulo'] ?></div>
                <div class="descripcion"><?= $anun['descripcion'] ?></div>
                <div class="categoria"><?= $anun['categoria'] ?></div>
                <div class="precio"><?= $anun['precio'] ?></div>
                <div class="estado"><?= $anun['estado'] ?></div>
            </div>
            <a href="chats.php?anun_id=<?= $anun['id'] ?>">Chat</a>

        <?php endforeach; ?>
    </div>


</body>

</html>