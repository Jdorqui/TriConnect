<?php
include "db_connection.php";

$USERNAME = $_GET['USERNAME'];
$GET_CHATTERLY_USERNAME_QUERY = $CONN->query("SELECT CHATTERLY_USERNAME FROM USERS WHERE USERNAME = '$USERNAME'");
echo json_encode($GET_CHATTERLY_USERNAME_QUERY->fetch_assoc());

$CONN->close();