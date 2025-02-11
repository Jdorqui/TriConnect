<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['password'])) {
    echo json_encode(["status" => "error", "message" => "Usuario o contraseña no válidos."]);
    exit();
}

try {
    // Obtener usuario y verificar contraseña
    $usuario = $_SESSION['usuario'];
    $password = $_SESSION['password'];

    $stmt = $pdo->prepare("SELECT id_user, password FROM usuarios WHERE username = :usuario");
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        $usuario_id = $row['id_user']; // El id_user se obtiene si la contraseña es correcta
    } else {
        echo json_encode(["status" => "error", "message" => "Usuario o contraseña incorrectos."]);
        exit();
    }

    // Procesar la solicitud
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $accion = $_POST['accion'];
        $solicitante_id = $_POST['solicitante'];

        $sql = ($accion === 'aceptar') ?
            "UPDATE amigos SET estado = 'aceptado' WHERE ((id_user1 = :solicitante_id AND id_user2 = :usuario_id) OR (id_user1 = :usuario_id AND id_user2 = :solicitante_id)) AND estado = 'pendiente'" :
            "DELETE FROM amigos WHERE ((id_user1 = :solicitante_id AND id_user2 = :usuario_id) OR (id_user1 = :usuario_id AND id_user2 = :solicitante_id)) AND estado = 'pendiente'";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':solicitante_id', $solicitante_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => $accion === 'aceptar' ? "Solicitud aceptada." : "Solicitud rechazada."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al procesar la solicitud."]);
        }
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>