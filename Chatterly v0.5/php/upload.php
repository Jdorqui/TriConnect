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

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) 
    {
        // Obtener el nombre del usuario (puedes modificar para usar sesiones o entradas dinámicas)
        $userName = $usuario ?? 'default_user'; // Cambia según tu lógica de usuario
        $baseDir = "../assets/users/";
        $userDir = $baseDir . $userName;
        $targetDir = $userDir . "/img_profile/";

        // Crear las carpetas si no existen
        if (!is_dir($userDir)) 
        {
            mkdir($userDir, 0777, true);
        }
        if (!is_dir($targetDir)) 
        {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($_FILES['profile_picture']['name']);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $targetFile = $targetDir . uniqid() . '.' . $fileType; // Nombre único para evitar conflictos

        // Validar tipo de archivo
        if (in_array($fileType, ['png', 'jpg', 'jpeg'])) 
        {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) 
            {
                echo json_encode(['success' => true, 'newImagePath' => $targetFile]);
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