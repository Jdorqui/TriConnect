<?php
include "db_connection.php";

session_start();

$USERNAME = $_SESSION['USERNAME'];
$GET_ALL_FRIENDS_QUERY = $CONN->
    query(
        "SELECT
                    s1.SUBSCRIBED_TO
                FROM
                    SUBS s1
                WHERE
                    s1.USERNAME = 'a' AND EXISTS(
                    SELECT
                        s2.USERNAME
                    FROM
                        SUBS s2
                    WHERE
                        s2.USERNAME = s1.SUBSCRIBED_TO AND s2.SUBSCRIBED_TO = s1.USERNAME
                )"
    );

$FRIENDS_ARRAY = json_encode($GET_ALL_FRIENDS_QUERY->fetch_all());
// echo $FRIENDS_ARRAY;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mensajes</title>
    <link rel="stylesheet" href="../css/main_css.css" />
    <link rel="stylesheet" href="../css/message.css" />
    <link rel="icon" type="image/x-icon" href="../img/mytube_logo.png" />
    <script>
        let username = '<?php echo $USERNAME ?>';
        let friendsArray = JSON.parse('<?php echo $FRIENDS_ARRAY ?>');
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
            <div class="content" style="background-color: gray; padding: 0">
                <div id="friend_navbar" style="background-color: green; width: 20%; min-width: 0px;">
                    <div style="text-align: center; font-size: 50px">Amigos</div>
                </div>
                <div style="width: 100%; height: 100%; display: flex; flex-direction: column; padding: 0">
                    <div id="user_header" style="background-color: red; width: 100%; display: flex">
                        <img src="../img/profile_pic_example.jpg" id="logged_pic">
                        <div>
                            <?php
                            echo 'z';
                            ?>
                        </div>
                    </div>
                    <div id="chat" style="background-color: black; flex-grow: 1; overflow: scroll;">
                    </div>
                    <div style="height: fit-content;">
                        <input id="input_text" type="text" placeholder="Enviar mensaje"
                            onkeypress="sendMessage(this, event)"
                            style="width: 100%; box-sizing: border-box; padding: 10px; margin: 0">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="notifications">
    </div>

    <script src="../js/main_js.js"></script>
    <script src="../js/messages_js.js"></script>
</body>

</html>