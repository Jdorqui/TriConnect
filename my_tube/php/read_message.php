<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$SENDER = $_POST["sender"];
$RECEIVER = $_POST["receiver"];

$READ_ALL_MSGS_QUERY = $CONN->
    query(
        "UPDATE
                    MSGS
                SET SEEN = 1
                WHERE
                    SEEN = 0 AND (
                    (SENDER = '$SENDER' AND RECEIVER = '$RECEIVER')
                    OR
                    (SENDER = '$RECEIVER' AND RECEIVER = '$SENDER'));
                "
    );

echo "UPDATE MSGS SET SEEN = 1 WHERE SEEN = 0 AND ((SENDER = '$SENDER' AND RECEIVER = '$RECEIVER') OR (SENDER = '$RECEIVER' AND RECEIVER = '$SENDER'));";