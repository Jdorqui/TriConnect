<?php
include "db_connection.php";

$USERNAME = $_POST['USERNAME'];
$GET_ALL_FRIENDS_QUERY = $CONN->
    query(
        "SELECT
                    s1.SUBSCRIBED_TO
                FROM
                    SUBS s1
                WHERE
                    s1.USERNAME = '$USERNAME' AND EXISTS(
                    SELECT
                        s2.USERNAME
                    FROM
                        SUBS s2
                    WHERE
                        s2.USERNAME = s1.SUBSCRIBED_TO AND s2.SUBSCRIBED_TO = s1.USERNAME
                )"
    );

$DATA = array();
while ($ROW = $GET_ALL_MSGS_QUERY->fetch_assoc()) {
    $DATA[] = $ROW;
}

echo json_encode($DATA);

$CONN->close();