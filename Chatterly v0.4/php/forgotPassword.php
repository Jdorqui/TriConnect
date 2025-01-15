<?php
// Configuración de la base de datos
$host = 'localhost'; // Cambiar si es necesario
$db = 'chatterly';
$user = 'root'; // Cambiar por tu usuario
$pass = ''; // Cambiar por tu contraseña
$charset = 'utf8mb4';

// Configurar la conexión a la base de datos
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try 
{
    $pdo = new PDO($dsn, $user, $pass, $options);
}
catch (\PDOException $e) 
{
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Si se envió el correo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) 
{
    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Si el correo está registrado
    if ($user) 
    {
        echo "<script>document.getElementById('new-password-fields').style.display = 'block';</script>";

        // Si se envió una nueva contraseña
        if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) 
        {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // Verificar si las contraseñas coinciden
            if ($new_password === $confirm_password) 
            {
                // Encriptar la nueva contraseña
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Actualizar la contraseña en la base de datos
                $update_stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
                $update_stmt->execute([$hashed_password, $email]);

                echo "<script>document.getElementById('mensaje').innerText = 'Contraseña restablecida exitosamente';</script>";
            } 
            else 
            {
                echo "<script>document.getElementById('mensaje').innerText = 'Las contraseñas no coinciden';</script>";
            }
        }
    } 
    else 
    {
        echo "<script>document.getElementById('mensaje').innerText = 'El correo no está registrado';</script>";
    }
}
?>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chatterly</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../assets/imgs/logo_bg.ico">
    </head>
    <body>
        <div id="login" class="active">
            <form action="forgotPassword.php" method="POST">
                <div style="margin: 0px;">
                    <div align-items="center class="input-group"">
                        <div>
                            <h1 id="ms1">Recuperar cuenta</h1>
                        </div>
                        <div>
                            <label for="email">Introduce un correo asociado a la cuenta *</label>
                            <input type="email" name="email" id="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
                        </div>
                        <div>
                            <label for="new_password">Nueva contraseña *</label>
                            <input type="password" name="new_password" id="new_password" required>
                        </div>
                        <div>
                            <label for="confirm_password">Confirmar nueva contraseña *</label>
                            <input type="password" name="confirm_password" id="confirm_password" required>
                        </div>
                        <div style="display: flex; gap: 8px; justify-content:">
                            <button type="submit" id="btn-submit-login">Restablecer Contraseña</button>
                            <button type="button" onclick="window.location.href='../html/index.html'">Volver</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="mensaje"></div>
        </div>
        <script defer src="../javascript/js.js"></script>
    </body>
</html>