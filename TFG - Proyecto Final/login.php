<?php
// Iniciar sesión
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wallapop_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Comprobar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Validar que no estén vacíos
    if (!empty($email) && !empty($contrasena)) {
        // Escapar los valores para evitar inyecciones SQL
        $email = $conn->real_escape_string($email);
        $contrasena = $conn->real_escape_string($contrasena);

        // Buscar el usuario en la base de datos
        $sql = "SELECT * FROM usuarios WHERE correo_electronico = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Si el usuario existe, comprobar la contraseña
            $usuario = $result->fetch_assoc();
            if (password_verify($contrasena, $usuario['contrasena'])) {
                // Contraseña correcta, iniciar sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                $_SESSION['correo_electronico'] = $usuario['correo_electronico'];

                // Redirigir al inicio o panel de usuario
                header("Location: inicio.php");  // Redirigir a la página de inicio
                exit();  // Asegurarse de que no se ejecute código adicional
            } else {
                // Contraseña incorrecta
                $error = "La contraseña es incorrecta.";
            }
        } else {
            // Usuario no encontrado
            $error = "El correo electrónico no está registrado.";
        }
    } else {
        $error = "Por favor, rellena todos los campos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Walla</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="fondo-oscuro">
        <div class="contenedor-login">
            <button class="boton-regreso">&#8592;</button>
            <h1>¡Te damos la bienvenida!</h1>
            <?php if(isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
            <form action="login.php" method="POST">
                <div class="grupo-formulario">
                    <input type="email" id="email" name="email" placeholder="Dirección de e-mail" required>
                </div>
                <div class="grupo-formulario">
                    <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                </div>
                <div class="enlace-contrasena">
                    <a href="#">¿Has olvidado tu contraseña?</a>
                </div>
                <button type="submit" class="boton-login">Acceder a Walla</button>
            </form>
        </div> 
        <br>
        <footer class="pie-pagina"> 
            <p>Sitio protegido. Consulta la <a href="https://policies.google.com/privacy">Política de Privacidad</a> y los <a href="https://policies.google.com/terms">Términos del Servicio</a>.</p>
        </footer>
    </div>
</body>
</html>
