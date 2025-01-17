<?php
include_once 'logueado.php';

include_once 'config.php';

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
                <li><a href="cuenta.php" class="active">Mi cuenta</a></li>
                <li><a href="chats.php">Mensajes</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <div class="chat - window">


        <div class="lista-mensajes">

            <div class="texto"></div>
            <div class="fecha"></div>

        </div>


    </div>




    <footer>
        <p>&copy; 2025 DeTo'</p>
    </footer>




</body>

</html>