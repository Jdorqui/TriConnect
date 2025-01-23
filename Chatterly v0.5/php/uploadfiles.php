<?php
session_start(); // Iniciamos la sesión

if (!isset($_SESSION['usuario']) || !isset($_SESSION['password']))  // Si el usuario no ha iniciado sesión
{ 
    header("Location: index.html"); // Redirecciona al index
    exit();
}

// Recupera el usuario y contraseña de la sesión
$usuario = $_SESSION['usuario'];
$password = $_SESSION['password'];

// Asegúrate de que el archivo y destinatario estén presentes en la solicitud
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo']) && isset($_POST['destinatario'])) 
{
    // Obtener el nombre del usuario (puedes modificar para usar sesiones o entradas dinámicas)
    $userName = $usuario ?? 'default_user'; // Cambia según tu lógica de usuario
    $destinatario = $_POST['destinatario']; // Obtenemos el destinatario desde la solicitud
    $baseDir = "../assets/users/";
    $userDir = $baseDir . $userName;
    $targetDir = $userDir . "/chat_files/"; // Nuevo subdirectorio chat_files

    // Crear las carpetas si no existen
    if (!is_dir($userDir)) 
    {
        mkdir($userDir, 0777, true);
    }
    if (!is_dir($targetDir)) 
    {
        mkdir($targetDir, 0777, true);
    }

    $fileName = basename($_FILES['archivo']['name']);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $targetFile = $targetDir . uniqid() . '.' . $fileType; // Nombre único para evitar conflictos

    // Validar tipo de archivo
    if (in_array($fileType, ['png', 'jpg', 'jpeg', 'pdf', 'mp4', 'mp3', 'zip', 'txt'])) 
    {
        // Intentar mover el archivo al destino
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $targetFile)) 
        {
            echo json_encode(['success' => true, 'newFilePath' => $targetFile]); // Enviar respuesta de éxito
        } 
        else 
        {
            echo json_encode(['success' => false, 'error' => 'Error al mover el archivo.']);
        }
    } 
    else 
    {
        echo json_encode(['success' => false, 'error' => 'Tipo de archivo no permitido.']);
    }
} 
else 
{
    echo json_encode(['success' => false, 'error' => 'Solicitud inválida.']);
}
?>
