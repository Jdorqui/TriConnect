<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['password'])) {
    header("Location: index.html");
    exit();
}

$usuario = $_SESSION['usuario'];
$password = $_SESSION['password'];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=chatterly', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

// Obtener el ID del usuario actual
$stmt = $pdo->prepare("SELECT id_user FROM usuarios WHERE username = ?");
$stmt->execute([$usuario]);
$usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuarioData) {
    echo "Usuario no encontrado.";
    exit();
}
$id_usuario_actual = $usuarioData['id_user'];

// Obtener los mensajes entre el usuario actual y el destinatario
if (isset($_POST['destinatario'])) {
    $destinatario = $_POST['destinatario'];
    $stmt = $pdo->prepare("
        SELECT m.contenido, m.fecha_envio, u.alias
        FROM mensajes m
        JOIN usuarios u ON m.id_emisor = u.id_user
        WHERE (m.id_emisor = :id_usuario AND m.id_receptor = :destinatario)
           OR (m.id_emisor = :destinatario AND m.id_receptor = :id_usuario)
        ORDER BY m.fecha_envio ASC
    ");
    $stmt->execute(['id_usuario' => $id_usuario_actual, 'destinatario' => $destinatario]);
    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($mensajes); // Devuelve los mensajes en formato JSON
}

// Enviar un mensaje
if (isset($_POST['mensaje'])) {
    $mensaje = $_POST['mensaje'];
    $destinatario = $_POST['destinatario'];
    $stmt = $pdo->prepare("INSERT INTO mensajes (id_emisor, id_receptor, contenido, tipo) VALUES (:id_emisor, :id_receptor, :contenido, 'texto')");
    $stmt->execute([
        'id_emisor' => $id_usuario_actual,
        'id_receptor' => $destinatario,
        'contenido' => $mensaje
    ]);
    echo "Mensaje enviado";
}
?>
