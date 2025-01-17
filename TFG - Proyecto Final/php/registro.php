<?php

// conexión a la base de datos

include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = "El correo electrónico ya está registrado.";
    } else {

        // Se inserta un nuevo usuario en la db.

        $stmt = $conn->prepare("INSERT INTO usuarios (email, password) VALUES (:email, :password)");

        $stmt->bindParam(':email', $email);

        $stmt->bindParam(':password', $password_hash);

        $stmt->execute();

        header("Location:login.php");
        exit();
    }
}
?>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo' - Registro</title>
</head>

<body>

    <h1>Regístrate</h1>

    <form action="registro.php" method="POST">

        <input type="email" name="email" placeholder="Correo electrónico" required> <br>

        <input type="password" name="password" placeholder="Contraseña" required> <br>

        <button type="submit">Registrarme</button>

    </form>

    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>

</body>

</html>