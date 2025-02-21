<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$SENDER = $_POST["SENDER"];
$RECEIVER = $_POST["RECEIVER"];
$MSG = $_POST["MSG"];
$FROM_CHATTERLY = $_POST["FROM_CHATTERLY"] == 'undefined' ? 0 : 1;
$CONN->query("INSERT INTO MSGS (SENDER, RECEIVER, MSG, SEEN, CHATTERLY) VALUES ('$SENDER', '$RECEIVER', '$MSG', 0, $FROM_CHATTERLY)");

echo "SUCCESS";

$CONN->close();