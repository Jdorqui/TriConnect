<?php
session_start();
include_once 'config.php';

$usuario_id = $_SESSION['user_id'];

// Obtener los datos del usuario
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :usuario_id");
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $ciudad = $_POST['ciudad'];
    $codigo_postal = $_POST['codigo_postal'];

    // Procesar imagen si se sube una nueva
    if (!empty($_FILES['imagen_perfil']['tmp_name'])) {
        $imagen = file_get_contents($_FILES['imagen_perfil']['tmp_name']);

        $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, ciudad = :ciudad, 
                codigo_postal = :codigo_postal, imagen_perfil = :imagen_perfil WHERE id = :usuario_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':imagen_perfil', $imagen, PDO::PARAM_LOB);
    } else {
        $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, ciudad = :ciudad, 
                codigo_postal = :codigo_postal WHERE id = :usuario_id";
        $stmt = $conn->prepare($sql);
    }

    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
    $stmt->bindParam(':ciudad', $ciudad, PDO::PARAM_STR);
    $stmt->bindParam(':codigo_postal', $codigo_postal, PDO::PARAM_STR);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: perfil.php?success=true");
        exit();
    } else {
        echo "Error al actualizar los datos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Perfil</title>
    <link rel="stylesheet" href="../css/perfil.css">
</head>
<body>
    <div class="container">
        <h2>Tu perfil</h2>
        <div class="perfil-container">
            <div class="imagen-perfil">
                <?php if (!empty($usuario['imagen_perfil'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($usuario['imagen_perfil']); ?>" alt="Foto de perfil">
                <?php else: ?>
                    <img src="../img/default-profile.png" alt="Foto de perfil por defecto">
                <?php endif; ?>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="file" id="imagen_perfil" name="imagen_perfil">
                    <button type="submit" name="actualizar">Actualizar</button>
                </form>
            </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="ciudad">Ubicación:</label>
                    <input type="text" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($usuario['ciudad']); ?>">
                </div>
                <div class="form-group">
                    <label for="codigo_postal">Código Postal:</label>
                    <input type="text" id="codigo_postal" name="codigo_postal" value="<?php echo htmlspecialchars($usuario['codigo_postal']); ?>">
                </div>
                <button type="submit" name="actualizar">Actualizar</button>
            </form>
        </div>
    </div>
</body>
</html>
