<?php
ini_set(option:'display_erros', value: 1);

include_once 'logueado.php';
include_once 'config.php';



$stmt = $conn->prepare("SELECT * FROM anuncios WHERE estado = 'activo' OR estado = 'reservado'");  

$stmt->execute();

$anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anuncios - ToDo'</title>
</head>
<body>
    

<h1>Anuncios</h1>

<div id="anuncios-list" class="anuncios-list">
                    <?php foreach ($anuncios as $anun): ?>
                        <div class="anuncio">

                        <a href="anuncio_info.php?id=<?php echo $anun['id'] ?>">
                            <div class="titulo"><?= $anun['titulo'] ?></div>

                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>


</body>
</html>