<?php
require 'db_connection.php';

ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $USERNAME = trim($_GET['username']);
    $CHANNEL_ID = trim($_GET['channel_id']);
}

$SUBSCRIBE_QUERY = $CONN->query("INSERT INTO SUBS VALUES ('$USERNAME', '$CHANNEL_ID')");
$CHECK_CHANNEL_SUBSCRIBED_TO_USER_QUERY = $CONN->query("SELECT '1' FROM SUBS WHERE USERNAME = '$CHANNEL_ID' AND SUBSCRIBED_TO = '$USERNAME'");

if ($CHECK_CHANNEL_SUBSCRIBED_TO_USER_QUERY->num_rows == 1) {
    echo '1';
} else {
    echo '0';
}

$CONN->CLOSE();