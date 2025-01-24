<?php
include_once 'logueado.php';
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo' - Inicio</title>
    <link rel="stylesheet" href="../css/inicio.css">
</head>

<body>

    <header>
        <div class="logo">
            <img src="../img/logoToDo.png" alt="Logo de ToDo'">
        </div>

        <!-- Contenedor para los iconos a la derecha -->
        <div class="header-icons">
            <!-- Icono de mensajes -->
            <a href="chats.php">
                <img src="../img/icono-mensajes.png" alt="Mensajes">
            </a>
            <!-- Icono de configuración -->
            <a href="cuenta.php">
                <img src="../img/icono-configuracion.png" alt="Configuración">
            </a>
            <!-- Icono para crear anuncio -->
            <a href="crear_anuncio.php">
                <img src="../img/icono-anuncio.png" alt="Crear Anuncio">
            </a>
        </div>
    </header>

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
