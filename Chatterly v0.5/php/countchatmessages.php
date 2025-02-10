

<?php
    session_start();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM mensajes WHERE id_mensaje = :id_mensaje" AND "id_emisor = :id_receptor" AND "id_receptor = :id_emisor");
    $stmt->bindParam(':id_mensaje', $_POST['id_mensaje']);
    $stmt->bindParam(':id_receptor', $_POST['id_receptor']);
    $stmt->bindParam(':id_emisor', $_POST['id_emisor']);
    $stmt->execute();
    $result = $stmt->fetch();
    echo $result[0];
?>