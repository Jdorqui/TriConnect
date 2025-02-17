<?php
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