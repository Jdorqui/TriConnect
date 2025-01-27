<?php
include_once 'logueado.php';
include_once 'config.php';

// Recuperar el ID del usuario actual (lo puedes obtener de la sesión)
$user_id = $_SESSION['user_id'];

// Consulta para obtener los productos del usuario logueado
$stmt = $conn->prepare("SELECT * FROM anuncios WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi cuenta</title>
    <link rel="stylesheet" href="../css/cuenta.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../img/logoToDo.png" alt="Logo de DeTo'">
        </div>
        <nav>
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="cuenta.php" class="active">Mi cuenta</a></li>
                <li><a href="chats.php">Mensajes</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Mis Productos</h1>
        <p>Aquí podrás gestionar los productos que tienes a la venta.</p>

        <div class="productos">
    <?php if (count($productos) > 0): ?>
        <?php foreach ($productos as $producto): ?>
            <div class="producto">
                <!-- Mostrar imagen del producto -->
                <img src="<?php echo $producto['imagen'] ? $producto['imagen'] : '../img/placeholder.jpg'; ?>" 
                     alt="Imagen del producto" class="producto-imagen">

                <!-- Información del producto -->
                <div class="producto-info">
                    <h3 class="titulo"><?php echo htmlspecialchars($producto['titulo']); ?></h3>
                    <p class="precio"><?php echo htmlspecialchars($producto['precio']); ?> €</p>
                    <p class="estado"><?php echo ucfirst(htmlspecialchars($producto['estado'])); ?></p>
                    <p class="fechas">
                        Publicado: <?php echo date('d/m/Y', strtotime($producto['fecha_publicacion'])); ?>
                    </p>
                </div>

                <!-- Botones de acción -->
                <div class="producto-acciones">
                    
                    <a href="editar_anuncio.php?id=<?php echo $producto['id']; ?>" class="btn-editar">Editar</a>
                    <form action="eliminar_anuncio.php" method="get" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este anuncio?');">
                        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                        <button type="submit" class="btn-eliminar">Eliminar</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tienes productos en venta actualmente.</p>
    <?php endif; ?>
</div>

    </main>

    <footer>
        <p>&copy; 2025 DeTo'</p>
    </footer>
</body>

</html>
