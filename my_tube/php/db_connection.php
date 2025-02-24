<?php
$SERVER_NAME = "localhost";
$SERVER_USERNAME = "root";
$SERVER_PASSWORD = "";
$DATABASE_NAME = "MYTUBE";
// $BASE_DIR = "/opt/lampp/htdocs/";
$BASE_DIR = "C:/xampp/htdocs/";
try {
    $CONN = new mysqli($SERVER_NAME, $SERVER_USERNAME, $SERVER_PASSWORD, $DATABASE_NAME);
} catch (Exception $e) {
    if (!str_contains($e, 'Access denied for user')) {
        echo "ERROR-000: Conexión fallida con la base de datos. " . $e->getMessage();
        die(0);
    }

    $SERVER_PASSWORD = "root";
    $CONN = new mysqli($SERVER_NAME, $SERVER_USERNAME, $SERVER_PASSWORD, $DATABASE_NAME);
}