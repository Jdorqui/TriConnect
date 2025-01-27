<?php
// Incluir los archivos necesarios para la sesión y la conexión a la base de datos
include_once 'logueado.php';
include_once 'config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Obtener los datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    
    // Manejo de la imagen
    $imagen = NULL;  // Si no se sube ninguna imagen, se dejará como NULL

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        // Verifica si hay errores en el archivo
        if ($_FILES['imagen']['error'] != UPLOAD_ERR_OK) {
            echo "Error al subir la imagen.";
        }

        // Validación del tipo MIME de la imagen
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['imagen']['type'], $allowed_mime_types)) {
            echo "El archivo no es una imagen válida.";
        } else {
            // Obtener los datos binarios de la imagen
            $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        }
    }

    // Insertar el anuncio en la base de datos con la imagen en formato binario
    $stmt = $conn->prepare("INSERT INTO anuncios (user_id, titulo, descripcion, precio, categoria, estado, imagen) 
                            VALUES (:user_id, :titulo, :descripcion, :precio, :categoria, 'activo', :imagen)");

    // Vincular parámetros
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
    $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
    $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);  // Usamos PDO::PARAM_LOB para manejar datos binarios

    $stmt->execute();

    // Redirigir a la página de inicio después de crear el anuncio
    header("Location: inicio.php");
    exit();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Anuncio - ToDo'</title>
    <link rel="stylesheet" href="../css/crear_anuncio.css">
</head>
<body>
    <h1>Crear Anuncio</h1>

    <!-- Formulario para crear el anuncio -->
    <form action="crear_anuncio.php" method="POST" enctype="multipart/form-data">
    <!-- Carga de imagen personalizada -->
    <div class="upload-container">
        <label for="imagen" class="upload-label">
            <span class="upload-text">Haz clic o arrastra para subir la imagen</span>
            <input type="file" id="imagen" name="imagen" accept="image/*" required class="upload-input">
        </label>
        <div id="preview-container" class="preview-container">
            <img id="preview-image" src="" alt="Vista previa" class="preview-image" style="display: none;">
        </div>
    </div>

    <!-- Otros campos del formulario -->
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" required><br><br>
    
    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required></textarea><br><br>
    
    <label for="precio">Precio:</label>
    <input type="number" id="precio" name="precio" required><br><br>
    
    <label for="categoria">Categoría:</label>
    <select id="categoria" name="categoria" required>
        <option value="electronica">Electrónica</option>
        <option value="moda">Ropa</option>
        <option value="otros">Vehículos</option>
        <option value="otros">Hogar</option>
    </select><br><br>

    <input type="submit" value="Crear Anuncio">
</form>
<script>
    // Mostrar vista previa de la imagen cuando el usuario la seleccione
    document.getElementById('imagen').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('preview-image');

        if (file) {
            // Crear un objeto URL para mostrar la imagen seleccionada
            const objectURL = URL.createObjectURL(file);
            previewImage.src = objectURL;
            previewImage.style.display = 'block';  // Mostrar la imagen

            // Mostrar el contenedor de la vista previa
            previewContainer.style.display = 'block';
        } else {
            // Si no hay imagen seleccionada, ocultar la vista previa
            previewImage.style.display = 'none';
            previewContainer.style.display = 'none';
        }
    });
</script>


</body>
</html>
