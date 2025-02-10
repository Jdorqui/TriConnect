<?php

include_once 'logueado.php';
include_once 'config.php';

$user_id = $_SESSION['user_id'];



$stmt = $conn->prepare("
SELECT COUNT(*) 
FROM mensajes 
WHERE chatId = :chat_id 
AND receiver_id = :user_id 
");

$stmt->bindParam(':chat_id', $_GET['chat_id'], PDO::PARAM_INT);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$mensajes = $stmt->fetch(PDO::FETCH_ASSOC);


echo json_encode($mensajes);

?>
