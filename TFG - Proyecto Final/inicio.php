<?php
// Iniciar sesión
session_start();



// Datos del usuario (de la sesión)
$usuario_id = $_SESSION['usuario_id'];
$nombre_usuario = $_SESSION['nombre_usuario'];
$correo_electronico = $_SESSION['correo_electronico'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Walla</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="img/logoWalla.png" alt="Logo de Wallapop">
        </div>
        <nav>
            <ul>
                <li><a href="inicio.php" class="active">Inicio</a></li>
                <li><a href="mensajes.php">Mensajes</a></li>
                <li><a href="mi_cuenta.php">Mi Cuenta</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li> <!-- Enlace para cerrar sesión -->
            </ul>
        </nav>
    </header>

    <section class="bienvenida">
        <h1>Bienvenido, <?php echo $nombre_usuario; ?>!</h1>
        <p>Correo electrónico: <?php echo $correo_electronico; ?></p>
        <!-- Contenido de la página de inicio -->
    </section>

    <footer>
        <p>&copy; 2025 Wallapop</p>
    </footer>
</body>
</html>
