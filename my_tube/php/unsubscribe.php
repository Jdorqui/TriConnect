<?php
require 'db_connection.php';

ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $USERNAME = trim($_GET['username']);
    $CHANNEL_ID = trim($_GET['channel_id']);
}

$UNSUBSCRIBE_QUERY = $CONN->query("DELETE FROM SUBS WHERE USERNAME = '$USERNAME' AND SUBSCRIBED_TO = '$CHANNEL_ID'");

$CONN->CLOSE();