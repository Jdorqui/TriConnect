<?php

include_once 'logueado.php';
include_once 'config.php';

ini_set(option:'display_erros', value: 1);

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];

    $stmt = $conn->prepare("INSERT INTO anuncios (user_id, titulo, descripcion, precio, categoria, estado) VALUES (:user_id, :titulo, :descripcion, :precio, :categoria, 'activo')");

    $stmt->execute();

    header("Location:inicio.php");
    exit();

}


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>


<form action=" crear_anuncio.php" method="POST">
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