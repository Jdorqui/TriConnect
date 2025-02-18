<?php
require 'conexion.php';

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE mytube = ?");
$stmt->execute([$_GET['mytube']]);

if($stmt->fetch() !== false)
{
    echo "ERROR";
    return;
}

$stmt = $pdo->prepare("UPDATE usuarios SET mytube = ? WHERE id_user = ?");
$stmt->execute([$_GET['mytube'], $_GET['id_user']]);
echo "Usuario registrado en MyTube exitosamente.";


?>