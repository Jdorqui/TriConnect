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

// Comprobar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los valores del formulario
    $nombre = $_POST['name'];
    $apellido = $_POST['surname'];
    $fecha_nacimiento = $_POST['birthdate'];

    // Comprobar si se ha subido una nueva imagen de perfil
    if (isset($_FILES['profile-image']) && $_FILES['profile-image']['error'] === UPLOAD_ERR_OK) {
        // Procesar la imagen (subirla al servidor, etc.)
        $target_dir = "uploads/"; // Ruta donde se guardarán las imágenes
        $target_file = $target_dir . basename($_FILES["profile-image"]["name"]);
        move_uploaded_file($_FILES["profile-image"]["tmp_name"], $target_file);
        $imagen_perfil = $target_file;
    } else {
        // Si no se ha subido una nueva imagen, usar la actual
        $imagen_perfil = null; // o el valor actual de la imagen, si es necesario
    }

    // Actualizar la base de datos
    $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellido, fecha_nacimiento = :fecha_nacimiento, imagen_perfil = :imagen_perfil WHERE id = :user_id";
    
    try {
        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        
        // Enlazar los parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        // Si se ha subido una nueva imagen, se incluye la ruta
        $stmt->bindParam(':imagen_perfil', $imagen_perfil);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Redirigir a la página de perfil actualizado
        header("Location: micuenta.php");
        exit();
    } catch (PDOException $e) {
        // Manejo de errores si la consulta falla
        echo "Error: " . $e->getMessage();
    }
}
?>
