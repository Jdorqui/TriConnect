<?php
// Iniciar sesión para comprobar si el usuario está logueado
session_start();

// Asegurarse de que el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirigir al login si no está logueado
    exit();
}

// Incluir el archivo de configuración para la conexión a la base de datos
include('config.php'); // Ajusta la ruta de config.php si es necesario

// Obtener el ID del usuario logueado desde la sesión
$user_id = $_SESSION['user_id'];

// Consulta para obtener los datos del usuario logueado
$sql = "SELECT alias, email, nombre, apellidos, fecha_nacimiento, fecha_registro, imagen_perfil FROM usuarios WHERE id = :user_id";

try {
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    
    // Vincular el parámetro usando bindParam
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener los resultados
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Si no se encuentra el usuario, redirigir a la página de error o login
    if (!$user) {
        header("Location: login.php");
        exit();
    }
    
    // Asignar valores de la base de datos a variables
    $alias = $user['alias'];
    $email = $user['email'];
    $nombre = $user['nombre'];
    $apellidos = $user['apellidos'];
    $fecha_nacimiento = $user['fecha_nacimiento'];
    $fecha_registro = $user['fecha_registro']; // Fecha de registro
    $imagen_perfil = $user['imagen_perfil']; // Ruta de la imagen de perfil
} catch (PDOException $e) {
    // Manejo de errores si la consulta falla
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Wallapop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1d/Wallapop_Logo.svg/1280px-Wallapop_Logo.svg.png" alt="Logo de Wallapop">
        </div>
        <nav>
            <ul>
                <li><a href="inicio.html">Inicio</a></li>
                <li><a href="buscar.html">Buscar</a></li>
                <li><a href="vender.html">Vender</a></li>
                <li><a href="micuenta.php" class="active">Mi Cuenta</a></li>
            </ul>
        </nav>
    </header>

    <section class="account-section">
        <div class="profile-card">
            <!-- Imagen y nombre de perfil cargados dinámicamente -->
            <img src="<?php echo htmlspecialchars($imagen_perfil); ?>" alt="Foto de Perfil" class="profile-img" id="profile-img">
            <h2 id="profile-name"><?php echo htmlspecialchars($alias); ?></h2>
            <p id="profile-location">Correo electrónico: <?php echo htmlspecialchars($email); ?></p>
            <p id="profile-location">Fecha de registro: <?php echo htmlspecialchars($fecha_registro); ?></p>
            <button class="btn-edit-profile" id="edit-profile-btn">Editar perfil</button>
        </div>

        <!-- Formulario de edición de perfil (inicialmente oculto) -->
        <div class="edit-profile-form" id="edit-profile-form">
            <h3>Editar perfil</h3>
            <form id="edit-profile" action="update_profile.php" method="POST" enctype="multipart/form-data">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($nombre); ?>" required>

                <label for="surname">Apellido:</label>
                <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($apellidos); ?>" required>

                <label for="birthdate">Fecha de nacimiento:</label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($fecha_nacimiento); ?>" required>

                <!-- La fecha de registro no es editable -->
                <label for="created_at">Fecha de Registro:</label>
                <input type="text" id="created_at" name="created_at" value="<?php echo htmlspecialchars($fecha_registro); ?>" disabled>

                <label for="profile-image">Foto de perfil:</label>
                <input type="file" id="profile-image" name="profile-image" accept="image/*">

                <button type="submit">Guardar cambios</button>
                <button type="button" id="cancel-edit">Cancelar</button>
            </form>
        </div>

        <div class="account-actions">
            <h3>Opciones de Cuenta</h3>
            <ul>
                <li><a href="#">Mis Anuncios</a></li>
                <li><a href="mensajes.php">Ver Mensajes</a></li>
                <li><a href="#">Historial de Compras</a></li>
                <li><a href="#">Ajustes</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Wallapop</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
