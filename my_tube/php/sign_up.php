<?php
// error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $USERNAME = trim($_POST['USERNAME']);
    $PASSWORD = password_hash(trim($_POST['PASSWORD']), PASSWORD_DEFAULT);
    $EMAIL = trim($_POST['EMAIL']);
}

$SERVER_NAME = "localhost";
$SERVER_USERNAME = "root";
$SERVER_PASSWORD = "";
$DATABASE_NAME = "MYTUBE";

try {
    $CONN = new mysqli($SERVER_NAME, $SERVER_USERNAME, $SERVER_PASSWORD, $DATABASE_NAME);
} catch (Exception $e) {
    echo "ERROR-000: Conexión fallida con la base de datos. " . $e->getMessage();

    die(0);
}

$CHECK_EXISTING_USER_QUERY = $CONN->query("SELECT '1' FROM USERS WHERE USERNAME = '$USERNAME'");

if ($CHECK_EXISTING_USER_QUERY->num_rows == 0) {
    $SQL = "INSERT INTO USERS VALUES ('$USERNAME', '$PASSWORD', '$EMAIL', NOW());";

    $CONN->query($SQL);
} else {
    echo "ERROR-001: El usuario '$USERNAME' ya existe.";

    die(1);
}

echo "SUCCESS";

/*
$sql = "SELECT * FROM usuario";
ECHO $sql;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        ECHO "alias: " . $row["alias"] . " - password: " . $row["password"] . " " . $row["nombre"] . "<br>";
    }
} else {
    ECHO "0 results";
}
*/
$CONN->close();