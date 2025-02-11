<?php
require 'db_connection.php';

ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $USERNAME = trim($_POST['USERNAME']);
    $PASSWORD = password_hash(trim($_POST['PASSWORD']), PASSWORD_DEFAULT);
    $EMAIL = trim($_POST['EMAIL']);
}

$CHECK_EXISTING_USER_QUERY = $CONN->query("SELECT '1' FROM USERS WHERE USERNAME = '$USERNAME'");

if ($CHECK_EXISTING_USER_QUERY->num_rows == 0) {
    $SQL = "INSERT INTO USERS VALUES ('$USERNAME', '$PASSWORD', '$EMAIL', NOW());";

    $CONN->query($SQL);

    session_start();

    $_SESSION["USERNAME"] = $USERNAME;
    $_SESSION["PASSWORD"] = $PASSWORD;
} else {
    echo "ERROR-001: El usuario '$USERNAME' ya existe.";

    die(1);
}

echo "SUCCESS";

$CONN->close();