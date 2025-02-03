<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$SENDER = $_POST["sender"];
$RECEIVER = $_POST["receiver"];
$MSG = $_POST["msg"];

$SEND_MESSAGE_QUERY = $CONN->query("INSERT INTO MSGS (SENDER, RECEIVER, MSG, SEND_DATE, SEEN) VALUES ('$SENDER', '$RECEIVER', '$MSG', NOW(5), 0)");

$CONN->close();