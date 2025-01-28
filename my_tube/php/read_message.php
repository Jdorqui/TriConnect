<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$SENDER = $_POST["sender"];
$RECEIVER = $_POST["receiver"];

echo "UPDATE MSGS SET SEEN = 1 WHERE SEEN = 0 AND SENDER = '$RECEIVER' AND RECEIVER = '$SENDER';";

$READ_ALL_MSGS_QUERY = $CONN->
    query(
        "UPDATE MSGS SET SEEN = 1 WHERE SEEN = 0 AND SENDER = '$RECEIVER' AND RECEIVER = '$SENDER';"
    );

$CONN->close();