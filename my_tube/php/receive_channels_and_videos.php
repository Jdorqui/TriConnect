<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$SEARCH_QUERY = $_POST["search_query"];

$GET_USERNAMES_QUERY = $CONN->query("SELECT USERNAME FROM USERS WHERE USERNAME LIKE '%$SEARCH_QUERY%' LIMIT 10");
echo json_encode($GET_USERNAMES_QUERY->fetch_all());

$CONN->close();