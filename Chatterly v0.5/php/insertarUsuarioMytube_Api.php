<?php
require 'conexion.php';

$stmt = $pdo->prepare("UPDATE usuarios SET mytube = ? WHERE id_user = ?");
$stmt->execute([$_GET['mytube'], $_GET['id_user']]);
echo "Usuario registrado en MyTube exitosamente.";
?>