<?php
include_once 'logueado.php';
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $anuncio_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id']; // Obtener el ID del usuario actual desde la sesión

    // Verificar si el anuncio pertenece al usuario logueado
    $stmt = $conn->prepare("SELECT * FROM anuncios WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $anuncio_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $anuncio = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($anuncio) {
        // Eliminar el anuncio
        $delete_stmt = $conn->prepare("DELETE FROM anuncios WHERE id = :id");
        $delete_stmt->bindParam(':id', $anuncio_id, PDO::PARAM_INT);

        if ($delete_stmt->execute()) {
            // Redirigir con mensaje de éxito
            header("Location: cuenta.php?mensaje=Anuncio eliminado con éxito");
            exit();
        } else {
            // Redirigir con mensaje de error
            header("Location: cuenta.php?mensaje=No se pudo eliminar el anuncio");
            exit();
        }
    } else {
        // Anuncio no encontrado o no pertenece al usuario
        header("Location: cuenta.php?mensaje=Anuncio no encontrado");
        exit();
    }
} else {
    // Solicitud no válida
    header("Location: cuenta.php?mensaje=Solicitud inválida");
    exit();
}
?>


