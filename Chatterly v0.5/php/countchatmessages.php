<?php
    session_start();
    require 'conexion.php';
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM mensajes WHERE id_emisor = ':destinatario' OR id_receptor = ':id_emisor'");
    $stmt->bindParam(':id_receptor', $_POST['id_receptor']);
    $stmt->bindParam(':id_emisor', $_POST['id_emisor']);
    $stmt->execute();
    $result = $stmt->fetch();
    echo $result[0];
?>