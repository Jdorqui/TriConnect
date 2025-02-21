<?php
require "db_connection.php";

ini_set('display_errors', 1);
session_start();

$_SESSION["USERNAME"] = $_GET["USERNAME"];
$_SESSION["PASSWORD"] = $_GET["PASSWORD"];

$CONN->close();