<?php
require 'db_connection.php';

ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $USERNAME = trim($_POST['USERNAME']);
    $PASSWORD = password_hash(trim($_POST['PASSWORD']), PASSWORD_DEFAULT);
    $EMAIL = trim($_POST['EMAIL']);
    $CHATTERLY_USERNAME = trim($_POST['USERNAME']);
}

$CHECK_EXISTING_USER_QUERY = $CONN->query("SELECT '1' FROM USERS WHERE USERNAME = '$USERNAME'");

if ($CHECK_EXISTING_USER_QUERY->num_rows == 0) {
    $SQL = "INSERT INTO USERS (USERNAME, PASSWORD, EMAIL, CHATTERLY_USERNAME) VALUES ('$USERNAME', '$PASSWORD', '$EMAIL', '$CHATTERLY_USERNAME');";

    $CONN->query($SQL);

    session_start();

    $_SESSION["USERNAME"] = $USERNAME;
    $_SESSION["PASSWORD"] = $PASSWORD;

    $USER_DIR = $BASE_DIR . "uploads/$USERNAME/";
    mkdir($USER_DIR, 0777, true);

    $FROM = $BASE_DIR . "DAM-B/TriConnect/my_tube/img/profile_pic.png";
    echo $FROM . "\n";
    $TO = $USER_DIR . "profile_pic.png";
    echo $TO;
    copy($FROM, $TO);

} else {
    echo "ERROR-001: El usuario '$USERNAME' ya existe.";

    die(1);
}

echo "SUCCESS";

$CONN->close();