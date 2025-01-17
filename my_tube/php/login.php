<?php
// error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $USERNAME = trim($_POST['USERNAME']);
    $PASSWORD = trim($_POST['PASSWORD']);
}

$SERVER_NAME = "localhost";
$SERVER_USERNAME = "root";
$SERVER_PASSWORD = "";
$DATABASE_NAME = "MYTUBE";

try {
    $CONN = new mysqli($SERVER_NAME, $SERVER_USERNAME, $SERVER_PASSWORD, $DATABASE_NAME);
} catch (Exception $e) {
    echo "ERROR-000: Conexión fallida con la base de datos. " . $e->getMessage();

    die(0);
}

$CHECK_EXISTING_USER_QUERY = $CONN->query("SELECT '1' FROM USERS WHERE USERNAME = '$USERNAME'");

if ($CHECK_EXISTING_USER_QUERY->num_rows == 0) {
    echo "ERROR-001: El usuario '$USERNAME' no existe.";

    die(1);
}

$CHECK_PASSWORD_QUERY = $CONN->query("SELECT PASSWORD FROM USERS WHERE USERNAME = '$USERNAME'");
$ROW = $CHECK_PASSWORD_QUERY->fetch_assoc();
if (password_verify($PASSWORD, $ROW['PASSWORD'])) {
    session_start();

    $_SESSION["USERNAME"] = $USERNAME;
    $_SESSION["PASSWORD"] = $PASSWORD;

    echo "SUCCESS";
} else {
    echo "ERROR-002: La contraseña del usuario '$USERNAME' no es correcta.";

    die(2);
}

$CONN->close();