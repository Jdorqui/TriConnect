<?php
require 'conexion.php';

$stmt = $pdo->prepare("SELECT estado FROM amigos JOIN usuarios ON amigos.id_user1 = usuarios.id_user OR amigos.id_user2 = usuarios.id_user WHERE :usuario1 = usuarios.alias AND
(SELECT estado FROM amigos JOIN usuarios ON amigos.id_user1 = usuarios.id_user OR amigos.id_user2 = usuarios.id_user WHERE :usuario2 = usuarios.alias) = 'aceptado'");
$stmt->bindParam(':usuario1', $_POST['usuario1'], PDO::PARAM_STR);
$stmt->bindParam(':usuario2', $_POST['usuario2'], PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>