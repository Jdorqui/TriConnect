<?php
$host = 'localhost'; 
$db = 'chatterly';
$user = 'root'; 
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; //configurar la conexion a la base de datos
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
    die("<div class='error'>Error de conexión a la base de datos</div>");
}

$mensaje = ""; //variable para almacenar el mensaje de error o exito

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) 
{
    $email = trim($_POST['email']);
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user)  
    {
        if (isset($_POST['new_password']) && isset($_POST['confirm_password']))  
        {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            //validar que la contraseña tenga al menos 5 caracteres, 1 mayúscula y 1 caracter especial
            if (!preg_match('/^(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{5,}$/', $new_password)) 
            {
                $mensaje = "La contraseña debe tener al menos 5 caracteres, una mayúscula y un carácter especial.";
            }
            elseif ($new_password !== $confirm_password)  
            {
                $mensaje = "Las contraseñas no coinciden.";
            }
            else 
            {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
                $update_stmt->execute([$hashed_password, $email]);
                $mensaje = "Contraseña restablecida exitosamente.";
            }
        }
    } 
    else 
    {
        $mensaje = "El correo no está registrado.";
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
        <style>
            .error { color: #f7767a; margin-top: 10px; }
            .success { color: green;  margin-top: 10px; }
        </style>
    </head>
    <body>
        <div id="login" class="active">
            <form action="forgotPassword.php" method="POST">
                <div style="margin: 0px;">
                    <div>
                        <h1 id="ms1">Recuperar cuenta</h1>
                    </div>
                    <div class="input-group" style="width: 100%;">
                        <label for="email">Introduce un correo asociado a la cuenta *</label>
                        <input type="email" name="email" id="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
                    </div>
                    <div class="input-group" style="width: 100%;">
                        <label for="new_password">Nueva contraseña *</label>
                        <input type="password" name="new_password" id="new_password" required>
                    </div>
                    <div class="input-group" style="width: 100%;">
                        <label for="confirm_password">Confirmar nueva contraseña *</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>
                    <div style="display: flex; gap: 8px; justify-content:">
                        <button type="submit" id="btn-submit-login">Restablecer Contraseña</button>
                        <button type="button" onclick="window.location.href='../html/index.html'">Volver</button>
                    </div>
                </div>
            </form>
            <div id="mensaje" class="<?php echo $mensaje ? (strpos($mensaje, 'exitosamente') !== false ? 'success' : 'error') : ''; ?>">
                <?php echo $mensaje; ?>
            </div>
        </div>
        <script defer src="../javascript/js.js"></script>
    </body>
</html>
