<?php
session_start();

// Conectar a la base de datos

include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificamos si el usuario existe en la db.

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificamos la contraseña.

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            header("Location:inicio.php");
            exit();

        } else {
            $error = "Contraseña incorrecta.";

        }
    } else {

        $error = "El correo electrónico no está registrado.";
    }
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo' - Login</title>
</head>

<body>
    <h1>Login</h1>

    <form action="login.php" method="POST">

        <input type="email" name="email" placeholder="Correo electrónico" required> <br>
        <input type="password" name="password" placeholder="Contraseña" required> <br>
        <button type="submit">Login</button>

        <p>¿No tienes una cuenta?<a href="registro.php">Registrarme</a></p>
    </form>
</body>

</html>