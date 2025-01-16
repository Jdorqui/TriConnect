<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $image = $_FILES['profile_picture'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_error = $image['error'];
    $image_size = $image['size'];

    if ($image_error === 0) {
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_exts = ['jpg', 'jpeg', 'png'];

        if (in_array(strtolower($image_ext), $allowed_exts)) {
            if ($image_size <= 5000000) {  // Max 5MB
                $new_image_name = "user_{$user_id}." . $image_ext;
                $upload_dir = 'uploads/';
                $upload_path = $upload_dir . $new_image_name;

                if (move_uploaded_file($image_tmp_name, $upload_path)) {
                    // Actualizar la base de datos con la nueva imagen
                    try {
                        $conn = new PDO("mysql:host=localhost;dbname=chat_app", 'root', '');
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $stmt = $conn->prepare("UPDATE usuarios SET imagen_perfil = :image WHERE id = :user_id");
                        $stmt->bindParam(':image', $upload_path);
                        $stmt->bindParam(':user_id', $user_id);
                        $stmt->execute();

                        $_SESSION['imagen_perfil'] = $upload_path;
                        header("Location: chat.php"); // Redirigir al chat
                        exit();
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "Error al mover el archivo.";
                }
            } else {
                echo "La imagen es demasiado grande.";
            }
        } else {
            echo "Tipo de archivo no permitido.";
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>
