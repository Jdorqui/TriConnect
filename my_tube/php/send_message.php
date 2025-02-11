<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$SENDER = $_POST["SENDER"];
$RECEIVER = $_POST["RECEIVER"];
$MSG = $_POST["MSG"];

$SEND_MESSAGE_QUERY = $CONN->query("INSERT INTO MSGS (SENDER, RECEIVER, MSG, SEND_DATE, SEEN) VALUES ('$SENDER', '$RECEIVER', '$MSG', NOW(5), 0)");

echo "SUCCESS";

$CONN->close();