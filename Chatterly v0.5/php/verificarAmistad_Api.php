<?php
require 'conexion.php';

$stmt = $pdo->prepare("SELECT estado FROM amigos WHERE (id_user1 = :usuario1 AND id_user2 = :usuario2) OR (id_user1 = :usuario2 AND id_user2 = :usuario1)");
echo json_encode(["SELECT estado FROM amigos WHERE (id_user1 = :usuario1 AND id_user2 = :usuario2) OR (id_user1 = :usuario2 AND id_user2 = :usuario1)"]);
$stmt->bindParam(':usuario1', $_POST['usuario1'], PDO::PARAM_STR);
$stmt->bindParam(':usuario2', $_POST['usuario2'], PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>