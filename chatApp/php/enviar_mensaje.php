<?php
session_start();
include '../db/conexion.php';

if (isset($_POST['mensaje']) && isset($_SESSION['user_id'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $mensaje = $_POST['mensaje'];

    $stmt = $conn->prepare("INSERT INTO mensajes (sender_id, receiver_id, mensaje) 
                            VALUES (:sender_id, :receiver_id, :mensaje)");
    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->bindParam(':mensaje', $mensaje);
    $stmt->execute();
}
?>
