<?php
ini_set('display_errors', 1);
include_once 'logueado.php';
include_once 'config.php';

// Recuperamos los anuncios activos, reservados o disponibles
$stmt = $conn->prepare("SELECT * FROM anuncios WHERE estado IN ('activo', 'reservado', 'disponible')");
$stmt->execute();

$anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anuncios - ToDo'</title>
    <link rel="stylesheet" href="../css/anunciosStyle.css">
</head>
<body>
    <h1>Anuncios</h1>

    <div id="anuncios-list" class="anuncios-list">
        <?php foreach ($anuncios as $anun): ?>
            <div class="anuncio">
                <a href="anuncio_info.php?id=<?php echo $anun['id'] ?>">
                    <!-- Mostrar imagen si está disponible en la base de datos -->
                    <?php if ($anun['imagen']): ?>
                        <?php 
                            // Convertir la imagen binaria a base64 para incrustarla en el HTML
                            $imagen_base64 = base64_encode($anun['imagen']);
                            // Obtener el tipo MIME de la imagen (esto debería estar guardado en la base de datos o asumirse)
                            $mime_type = 'image/jpeg'; // Cambiar según sea necesario (ej.: image/png)
                        ?>
                        <img src="data:<?php echo $mime_type; ?>;base64,<?php echo $imagen_base64; ?>" alt="Imagen del anuncio" class="imagen-anuncio">
                    <?php else: ?>
                        <!-- Si no hay imagen, mostrar un placeholder -->
                        <img src="placeholder.jpg" alt="Imagen no disponible" class="imagen-anuncio">
                    <?php endif; ?>
                    <div class="titulo"><?= $anun['titulo'] ?></div>
                    <div class="precio"><?= $anun['precio'] ?> €</div>
                    <div class="estado"><?= ucfirst($anun['estado']) ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>