<?php
// Conexión a la base de datos
include_once 'logueado.php';
include_once 'config.php';

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtener el parámetro de búsqueda
$query = isset($_GET['query']) ? '%' . $_GET['query'] . '%' : '';

// Consulta SQL
$sql = "SELECT * FROM anuncios
        WHERE 
            (titulo LIKE :query OR descripcion LIKE :query OR categoria LIKE :query)
        ORDER BY fecha_publicacion DESC";

// Preparar la declaración
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':query', $query, PDO::PARAM_STR);

// Ejecutar la consulta
$stmt->execute();

// Obtener los resultados
$anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de búsqueda</title>
    <link rel="stylesheet" href="../css/inicio.css">
</head>
<body>

    <header>
        <div class="logo">
            <img src="../img/logoToDo.png" alt="Logo de ToDo'">
        </div>
        <div class="header-icons">
            <a href="chats.php"><img src="../img/icono-mensajes.png" alt="Mensajes"></a>
            <a href="cuenta.php"><img src="../img/icono-configuracion.png" alt="Configuración"></a>
            <a href="crear_anuncio.php"><img src="../img/icono-anuncio.png" alt="Crear Anuncio"></a>
        </div>
    </header>

    <section class="resultados-busqueda">
        <h2>Resultados de la búsqueda</h2>

        <?php if (count($anuncios) > 0): ?>
            <div class="listado-categorias">
                <?php foreach ($anuncios as $anuncio): ?>
                    <div class="categoria-articulo">
                    <?php if ($anuncio['imagen']): ?>
                        <?php 
                            // Convertir la imagen binaria a base64 para incrustarla en el HTML
                            $imagen_base64 = base64_encode($anuncio['imagen']);
                            // Obtener el tipo MIME de la imagen (esto debería estar guardado en la base de datos o asumirse)
                            $mime_type = 'image/jpeg'; // Cambiar según sea necesario (ej.: image/png)
                        ?>
                        <img src="data:<?php echo $mime_type; ?>;base64,<?php echo $imagen_base64; ?>" alt="Imagen del anuncio" class="imagen-anuncio">
                    <?php else: ?>
                        <!-- Si no hay imagen, mostrar un placeholder -->
                        <img src="placeholder.jpg" alt="Imagen no disponible" class="imagen-anuncio">
                    <?php endif; ?>
                        <p><?php echo htmlspecialchars($anuncio['titulo']); ?></p>
                        <p>Precio: <?php echo $anuncio['precio']; ?>€</p>
                        <p>Descripción: <?php echo htmlspecialchars($anuncio['descripcion']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No se encontraron resultados.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 DeTo'</p>
    </footer>

</body>
</html>
