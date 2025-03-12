<?php
include_once 'logueado.php';  // Asegúrate de incluir el archivo que verifica si el usuario está logueado
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo' - Iniciooooooooo</title>
    <link rel="stylesheet" href="../css/inicio.css">
</head>

<body>

    <header>
        <div class="logo">
            <img src="../img/logoToDo.png" alt="Logo de ToDo'">
        </div>

        <!-- Contenedor para los iconos a la derecha -->
        <div class="header-icons">
            <a href="chats.php" style="text-decoration: none;">
                <img src="../img/imagenMensajes.png" alt="Mensajes">
            </a>
            <a href="cuenta.php">
                <img src="../img/perfil.png" alt="Perfil">
            </a>
            <a href="crear_anuncio.php">
                <img src="../img/vender.png" alt="Crear Anuncio">
            </a>
        </div>
    </header>

    <!-- Sección del buscador -->
    <section class="buscador">
        <form action="buscar_anuncios.php" method="get">
            <input type="text" name="query" placeholder="Buscar anuncios..." class="input-buscar" required>
            
            <button type="submit" class="btn-buscar">Buscar</button>
        </form>
    </section>

    <section class="categorias">
        <h2>Categorías</h2>
        <div class="listado-categorias">
            <div class="categoria-articulo">
                <img src="https://via.placeholder.com/150" alt="Categoría 1">
                <p>Electrónica</p>
            </div>
            <div class="categoria-articulo">
                <img src="https://via.placeholder.com/150" alt="Categoría 2">
                <p>Ropa</p>
            </div>
            <div class="categoria-articulo">
                <img src="https://via.placeholder.com/150" alt="Categoría 3">
                <p>Vehículos</p>
            </div>
            <div class="categoria-articulo">
                <img src="https://via.placeholder.com/150" alt="Categoría 4">
                <p>Hogar</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 DeTo'</p>
    </footer>

</body>
</html>
