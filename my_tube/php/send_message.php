<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$SENDER = $_POST["sender"];
$RECEIVER = $_POST["receiver"];
$MSG = $_POST["msg"];

$SEND_MESSAGE_QUERY = $CONN->query("INSERT INTO MSGS VALUES ('$SENDER', '$RECEIVER', '$MSG', NOW(6))");

$CONN->close();