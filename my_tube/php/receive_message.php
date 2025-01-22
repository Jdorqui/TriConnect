<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$SENDER = $_POST["sender"];
$RECEIVER = $_POST["receiver"];

$GET_ALL_MSGS_QUERY = $CONN->
    query(
        "SELECT
                    *
                FROM
                    MSGS
                WHERE
                    (SENDER = '$SENDER' AND RECEIVER = '$RECEIVER')
                    OR
                    (SENDER = '$RECEIVER' AND RECEIVER = '$SENDER')
                ORDER BY SEND_DATE;
                "
    );

echo json_encode($GET_ALL_MSGS_QUERY->fetch_all());