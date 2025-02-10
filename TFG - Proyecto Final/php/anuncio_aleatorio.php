<?php
// Conexión a la base de datos
$host = "localhost";  // o tu servidor de base de datos
$user = "root";       // usuario de base de datos
$password = "";       // tu contraseña
$dbname = "chat_app"; // nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para obtener un anuncio aleatorio
$sql = "SELECT * FROM anuncios ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    $anuncio = $result->fetch_assoc();
    $imagen = base64_encode($anuncio['imagen']);  // Convertir la imagen a base64
    $response = array(
        'titulo' => $anuncio['titulo'],
        'descripcion' => $anuncio['descripcion'],
        'precio' => $anuncio['precio'],
        'estado' => $anuncio['estado'],
        'imagen' => $imagen
    );
    echo json_encode($response);
} else {
    $response = array(
        'titulo' => "No hay anuncios disponibles",
        'descripcion' => "Intenta más tarde.",
        'precio' => 0.00,
        'estado' => "Desconocido",
        'imagen' => null
    );
    echo json_encode($response);
}

$conn->close();
?>
