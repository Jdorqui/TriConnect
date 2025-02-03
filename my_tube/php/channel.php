<?php
require 'db_connection.php';

ini_set('display_errors', 1);
session_start();

$USERNAME = $_SESSION['USERNAME'];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $CHANNEL_ID = $_GET['channel_id'];

    $CHECK_EXISTING_USER_QUERY = $CONN->query("SELECT '1' FROM USERS WHERE USERNAME = '$CHANNEL_ID'");
    if ($CHECK_EXISTING_USER_QUERY->num_rows == 0) {
        header('Location: home.php');
    }

    $CHECK_USER_SUBSCRIBED_QUERY = $CONN->query("SELECT '1' FROM SUBS WHERE USERNAME = '$USERNAME' AND SUBSCRIBED_TO = '$CHANNEL_ID'");
    $CHECK_CHANNEL_SUBSCRIBED_TO_USER_QUERY = $CONN->query("SELECT '1' FROM SUBS WHERE USERNAME = '$CHANNEL_ID' AND SUBSCRIBED_TO = '$USERNAME'");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Channel</title>
    <link rel="stylesheet" href="../css/main_css.css" />
    <link rel="stylesheet" href="../css/channel.css" />
    <link rel="icon" type="image/x-icon" href="../img/mytube_logo.png" />
    <script>
        let username = '<?php echo $USERNAME ?>';
        let channelID = '<?php echo $CHANNEL_ID ?>';
    </script>
</head>

<body>
    <div class="main">
        <div class="bar">
            <div class="mytube_logo_div" onclick="location.href='home.php'">
                <img src="../img/mytube_logo.png" id="mytube_logo" />My<span style="color: red">Tube</span>
            </div>

            <input tpye="text" class="search_bar" placeholder="Buscar vídeo" />

            <?php if (isset($_SESSION["USERNAME"]) && isset($_SESSION["PASSWORD"])): ?>
                <div class="user_logged_in_tab" onclick="displayUserSettings()">
                    <img src="../img/profile_pic_example.jpg" id="logged_pic">
                </div>
            <?php else: ?>
                <div class="user_tab" onclick="displayLoginAPIWrapper()">
                    <img src="../img/profile_pic.jpg" id="login_pic">
                    <div>Iniciar sesión</div>
                </div>
            <?php endif; ?>
        </div>
        <div class="nav_content_container">
            <div class="navbar">
                <div>
                    Home
                </div>
                <div>
                    Explore
                </div>
                <div>
                    Suscripciones
                </div>
                <div>
                    Historial
                </div>
                <div>
                    Liked vídeos
                </div>
            </div>
            <div class="content" style="flex-flow: column">
                <div style="width: 100%; height: fit-content; border-bottom: 2px white solid;">
                    <div id="user_channel">
                        <img src="../img/profile_pic_example.jpg" id="user_channel_profile_pic">
                        <div style="flex-grow: 1; display: flex; flex-direction: column; padding: 10px;">
                            <?php
                            echo $CHANNEL_ID;
                            ?>
                            <div style="display: flex; flex-grow: 1">
                                <?php if ($CHECK_CHANNEL_SUBSCRIBED_TO_USER_QUERY->num_rows > 0 && $CHECK_USER_SUBSCRIBED_QUERY->num_rows > 0): ?>
                                    <div class="subscribe_button" onclick="unsubscribe(username, channelID)"
                                        id="subscription" style="background-color: blue">Amigos</div>
                                    <div id="chat_button" onclick="location.href='chat.php'">Enviar mensaje</div>
                                <?php elseif ($CHECK_USER_SUBSCRIBED_QUERY->num_rows > 0): ?>
                                    <div class="subscribe_button" onclick="unsubscribe(username, channelID)"
                                        id="subscription">Suscrito</div>
                                <?php else: ?>
                                    <div class="subscribe_button" onclick="subscribe(username, channelID)"
                                        id="subscription">Suscribirse</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div id="channel_navbar">
                        <span>
                            Home
                        </span>
                        <span>
                            Videos
                        </span>
                        <span>
                            Live
                        </span>
                        <span>
                            Community
                        </span>
                        <span>
                            Search
                        </span>
                    </div>
                </div>
                <div style="background-color:blue; flex-grow: 1;">

                </div>
            </div>
        </div>
    </div>

    <script src="../js/main_js.js"></script>
    <script src="../js/channel_js.js"></script>
</body>

</html>