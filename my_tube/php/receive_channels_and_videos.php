<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$USERNAME = $_POST["username"];
$SEARCH_QUERY = $_POST["search_query"];

$GET_USERNAMES_QUERY = $CONN->query("SELECT USERNAME FROM USERS WHERE USERNAME LIKE '%$SEARCH_QUERY%' LIMIT 10");
$GET_SUBSCRIBED_TO_QUERY = $CONN->query("SELECT SUBSCRIBED_TO FROM SUBS WHERE SUBSCRIBED_TO LIKE '%$SEARCH_QUERY%' AND USERNAME = '$USERNAME'");
$GET_CHANNEL_SUBSCRIBED_TO_USER_QUERY = $CONN->query(
    "SELECT
                s1.SUBSCRIBED_TO
            FROM
                SUBS s1
            WHERE
                s1.SUBSCRIBED_TO LIKE '%$SEARCH_QUERY%' AND s1.USERNAME = '$USERNAME' AND EXISTS(
            SELECT
                s2.USERNAME
            FROM
                SUBS s2
            WHERE
                s2.USERNAME = s1.SUBSCRIBED_TO AND s2.SUBSCRIBED_TO = s1.USERNAME
        )"
);

$DATA = array();
$TEMP = array();
while ($ROW = $GET_USERNAMES_QUERY->fetch_assoc()) {
    $TEMP[] = $ROW["USERNAME"];
}

$DATA["usernames"] = $TEMP;
$TEMP = array();
while ($ROW = $GET_SUBSCRIBED_TO_QUERY->fetch_assoc()) {
    $TEMP[] = $ROW["SUBSCRIBED_TO"];
}

$DATA["subscribed"] = $TEMP;
$TEMP = array();
while ($ROW = $GET_CHANNEL_SUBSCRIBED_TO_USER_QUERY->fetch_assoc()) {
    $TEMP[] = $ROW["SUBSCRIBED_TO"];
}

$DATA["friends"] = $TEMP;

echo json_encode($DATA);

$CONN->close();