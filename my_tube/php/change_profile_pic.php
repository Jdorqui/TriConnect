<?php
require 'db_connection.php';

ini_set('display_errors', 1);
session_start();

$USERNAME = $_SESSION["USERNAME"];

$UPLOADED_FILE = $_FILES["profile_pic_input"];
$IMAGE_FILE_EXTENSION = strtolower(pathinfo($UPLOADED_FILE["name"], PATHINFO_EXTENSION));
$USER_DIR = $BASE_DIR . "uploads/$USERNAME/";
$FILE = $USER_DIR . "profile_pic." . $IMAGE_FILE_EXTENSION;

echo $FILE;

if (!file_exists($USER_DIR)) {
    mkdir($USER_DIR, 0777, true);
}

move_uploaded_file($UPLOADED_FILE["tmp_name"], $FILE);