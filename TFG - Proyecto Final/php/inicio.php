<?php

include_once 'logueado.php';

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo' - Inicio</title>
</head>

<body>

    <header>

        <div class="logo">

            <img src="../img/logoToDo.png" alt="Logo de ToDo'">


        </div>

        <nav>

            <ul>

                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="cuenta.php">Mi cuenta</a></li>
                <li><a href="chats.php">Mensajes</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>

            </ul>

        </nav>

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