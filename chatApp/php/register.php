<?php
session_start();

// Conectar a la base de datos
$host = 'localhost';
$db = 'chat_app';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_BCRYPT); // Encriptar la contraseña

    // Verificar si el correo ya está registrado
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = "El correo electrónico ya está registrado.";
    } else {
        // Insertar nuevo usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (email, password) VALUES (:email, :password)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->execute();

        // Redirigir al login después de un registro exitoso
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Regístrate</h1>
    <form action="register.php" method="POST">
        <input type="email" name="email" placeholder="Correo electrónico" required><br>
        <input type="password" name="password" placeholder="Contraseña" required><br>
        <button type="submit">Registrarme</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="index.php">Inicia sesión</a></p>

    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } // Mostrar errores ?>
</body>
</html>
