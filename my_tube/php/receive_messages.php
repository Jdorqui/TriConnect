<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$SENDER = $_POST["SENDER"];
$RECEIVER = $_POST["RECEIVER"];

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

$DATA = array();
while ($ROW = $GET_ALL_MSGS_QUERY->fetch_assoc()) {
    $DATA[] = $ROW;
}

echo json_encode($DATA);

$CONN->close();