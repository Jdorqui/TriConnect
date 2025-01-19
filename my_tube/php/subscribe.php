<?php
// error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $USERNAME = trim($_GET['username']);
    $CHANNEL_ID = trim($_GET['channel_id']);
}

$SERVER_NAME = "localhost";
$SERVER_USERNAME = "root";
$SERVER_PASSWORD = "root";
$DATABASE_NAME = "MYTUBE";

try {
    $CONN = new mysqli($SERVER_NAME, $SERVER_USERNAME, $SERVER_PASSWORD, $DATABASE_NAME);
} catch (Exception $e) {
    echo "ERROR-000: Conexión fallida con la base de datos. " . $e->getMessage();

    die(0);
}

$SUBSCRIBE_QUERY = $CONN->query("INSERT INTO SUBS VALUES ('$USERNAME', '$CHANNEL_ID')");

$CONN->CLOSE();