<?php
session_start(); // Asegúrate de iniciar la sesión

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['password'])) 
{
    echo json_encode(["status" => "error", "message" => "Usuario o contraseña no válidos."]);
    exit();
}

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "chatterly");

// Verifica la conexión
if ($mysqli->connect_error) 
{
    die("Error de conexión: " . $mysqli->connect_error);
}

// Obtener el ID del usuario logueado
$usuario = $_SESSION['usuario'];
$password = $_SESSION['password']; // Esto debe ser la contraseña original en texto plano

// Obtener el ID y la contraseña cifrada del usuario desde la base de datos
$sql = "SELECT id_user, password FROM usuarios WHERE username = '$usuario'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) 
{
    $row = $result->fetch_assoc();
    
    // Verificar la contraseña con password_verify
    if (password_verify($password, $row['password'])) 
    {
        $usuario_id = $row['id_user']; // El id_user se obtiene si la contraseña es correcta
    } 
    else 
    {
        die("Contraseña incorrecta.");
    }
} 
else 
{
    die("Usuario no encontrado.");
}

// Aquí procesas la solicitud
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $accion = $_POST['accion'];
    $solicitante_id = $_POST['solicitante'];

    // Lógica para aceptar o rechazar la solicitud
    if ($accion == 'aceptar') 
    {
        // Actualiza el estado de la solicitud a 'aceptado'
        $sql_update = "UPDATE amigos SET estado = 'aceptado' WHERE (id_user1 = '$solicitante_id' AND id_user2 = '$usuario_id') OR (id_user1 = '$usuario_id' AND id_user2 = '$solicitante_id') AND estado = 'pendiente'";
        if ($mysqli->query($sql_update)) 
        {
            echo "Solicitud aceptada.";
        } 
        else 
        {
            echo "Error al aceptar la solicitud: " . $mysqli->error;
        }
    } 
    elseif ($accion == 'rechazar') 
    {
        // Elimina la solicitud de la tabla amigos
        $sql_delete = "DELETE FROM amigos WHERE (id_user1 = '$solicitante_id' AND id_user2 = '$usuario_id') OR (id_user1 = '$usuario_id' AND id_user2 = '$solicitante_id') AND estado = 'pendiente'";
        if ($mysqli->query($sql_delete)) 
        {
            echo "Solicitud rechazada.";
        } 
        else 
        {
            echo "Error al rechazar la solicitud: " . $mysqli->error;
        }
    }

    // Redirige después de procesar la acción
    header('Location: chatterly.php');
    exit();
}
?>
