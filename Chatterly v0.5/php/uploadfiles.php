<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['password'])) {
    header("Location: index.html");
    exit();
}

$usuario = $_SESSION['usuario'];
$password = $_SESSION['password'];

// Conexión a la base de datos
try {
    $pdo = new PDO('mysql:host=localhost;dbname=chatterly', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

// Obtener el ID del usuario actual
$stmt = $pdo->prepare("SELECT id_user FROM usuarios WHERE username = ?");
$stmt->execute([$usuario]);
$usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuarioData) {
    echo "Usuario no encontrado.";
    exit();
}
$id_usuario_actual = $usuarioData['id_user'];

// Configuración de directorios y archivos
$baseDir = "../assets/users/"; // Cambié la barra de directorio para el sistema de archivos
$userName = isset($usuario) ? htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') : ''; // Escapar nombre de usuario
$destinatario = isset($_POST['destinatario']) ? htmlspecialchars($_POST['destinatario'], ENT_QUOTES, 'UTF-8') : ''; // Escapar destinatario
$targetDir = $baseDir . $userName . "/chat_files/"; // Directorio de destino

// Verificar si se ha recibido la solicitud correctamente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo']) && !empty($destinatario)) {

    // Crear los directorios si no existen
    if (!is_dir($baseDir . $userName)) {
        mkdir($baseDir . $userName, 0777, true);
    }
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Variables del archivo
    $fileName = basename($_FILES['archivo']['name']);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $targetFile = $targetDir . uniqid() . '.' . $fileType; // Nuevo nombre único para el archivo

    // Validar tamaño del archivo (máximo 10 MB)
    if ($_FILES['archivo']['size'] > 10485760) {
        echo json_encode(['success' => false, 'error' => 'El archivo excede el tamaño máximo permitido (10 MB).']);
        exit();
    }

    // Tipos de archivo permitidos
    $allowedFileTypes = ['png', 'jpg', 'jpeg', 'pdf', 'mp4', 'mp3', 'zip', 'txt'];
    
    // Validar tipo de archivo
    if (in_array($fileType, $allowedFileTypes)) {
        // Intentar mover el archivo a su destino final
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $targetFile)) {
            // Insertar el mensaje con el archivo adjunto en la base de datos
            $stmt = $pdo->prepare("INSERT INTO mensajes (id_emisor, id_receptor, contenido, tipo) VALUES (:id_emisor, :id_receptor, :contenido, 'archivo')");
            $stmt->execute([
                'id_emisor' => $id_usuario_actual,
                'id_receptor' => $destinatario,
                'contenido' => $targetFile
            ]);

            echo json_encode([
                'success' => true,
                'newFilePath' => $targetFile,
                'fileName' => $fileName
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al mover el archivo.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Tipo de archivo no permitido.']);
    }

} else {
    // Si la solicitud no es válida
    echo json_encode(['success' => false, 'error' => 'Solicitud inválida o falta destinatario.']);
}
?>
