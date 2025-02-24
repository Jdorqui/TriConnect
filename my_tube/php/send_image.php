<?php
require 'db_connection.php';

ini_set('display_errors', 1);

$SENDER = $_POST["SENDER"];
$RECEIVER = $_POST["RECEIVER"];
$IMAGE = $_FILES["IMAGE"];
$USER_DIR = $BASE_DIR . "uploads/$SENDER/";
$FILE = $USER_DIR . $IMAGE["name"];
$FROM_CHATTERLY = $_POST["FROM_CHATTERLY"] == 'undefined' ? 0 : 1;
move_uploaded_file($IMAGE["tmp_name"], $FILE);

$ACTUAL_STRING = $IMAGE["name"];
$CONN->query("INSERT INTO MSGS (SENDER, RECEIVER, MSG, SEEN, IS_FILE, CHATTERLY) VALUES ('$SENDER', '$RECEIVER', '$ACTUAL_STRING', 0, 1, $FROM_CHATTERLY)");

echo "SUCCESS";

$CONN->close();