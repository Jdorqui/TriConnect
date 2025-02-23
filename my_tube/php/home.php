<?php
require "db_connection.php";

session_start();

$USERNAME = "";
$FRIENDS_ARRAY = "";
$PROFILE_PIC = "";
if (isset($_SESSION['USERNAME'])) {
    $USERNAME = $_SESSION['USERNAME'];

    if (file_exists($BASE_DIR . "uploads/" . $USERNAME . "/profile_pic.png")) {
        $PROFILE_PIC = "../../../../../uploads/" . $USERNAME . "/profile_pic.png";
    }

    // GET RID OF THIS.
    $GET_ALL_FRIENDS_QUERY = $CONN->
        query(
            "SELECT
                        s1.SUBSCRIBED_TO
                    FROM
                        SUBS s1
                    WHERE
                        s1.USERNAME = '$USERNAME' AND EXISTS(
                        SELECT
                            s2.USERNAME
                        FROM
                            SUBS s2
                        WHERE
                            s2.USERNAME = s1.SUBSCRIBED_TO AND s2.SUBSCRIBED_TO = s1.USERNAME
                    )"
        );

    $FRIENDS_ARRAY = json_encode($GET_ALL_FRIENDS_QUERY->fetch_all());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyTube</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/login_api.css" />
    <link rel="stylesheet" href="../css/chat.css" />
    <link rel="stylesheet" href="../css/search.css" />
    <link rel="stylesheet" href="../css/settings.css" />
    <link rel="stylesheet" href="../css/channel.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <script>
        let username = "";
        if ('<?php echo $USERNAME ?>' != "") {
            username = '<?php echo $USERNAME ?>';
        }

        let profile_pic = "../img/profile_pic_example.jpg";
        if ('<?php echo $PROFILE_PIC ?>' != "") {
            profile_pic = '<?php echo $PROFILE_PIC ?>';
        }
    </script>
</head>

<body>
    <div id="main_div">
        <div id="header">
            <div>
                <img id="mytube_logo" src="../img/mytube_logo.png">
                My<span style="color: red">Tube</span>
            </div>
            <input id="search_input" type="text" placeholder="Buscar vídeo o canal">

            <!-- TODO -->
            <?php if (isset($_SESSION["USERNAME"])): ?>
                <div id="user_logged_in_tab" onclick="display('settings')">
                    <img class="every_user_image">
                    <div><?php echo $_SESSION["USERNAME"] ?></div>
                </div>
                <img src="../img/logout.png" onclick="logout()" style="height: 80%; margin-left: 1vw; cursor:pointer">
            <?php else: ?>
                <div id="user_logged_out_tab" onclick="displayLoginAPIWrapper()">
                    <img class="every_user_image" src="../img/logged_out_profile_pic.jpg">
                    <div>Iniciar sesión</div>
                </div>
            <?php endif; ?>
        </div>
        <div id="navbar_and_content">
            <div id="navbar">
                <img src="../img/home_icon.png" onclick="display('home')">
                <img src="../img/subs_icon.png" onclick="display('subs')">
                <img src="../img/history_icon.png" onclick="display('history')">
                <img src="../img/like_icon.png" onclick="display('like')">
                <div style="position: relative" onclick="display('chat')">
                    <img src="../img/chat_icon.png">
                    <div id="new_messages_chat_tab" style="display: none">
                        0
                    </div>
                </div>
            </div>
            <div id="content">
                <div id="home_div">HOME</div>
                <div id="subs_div">SUBS</div>
                <div id="history_div">HISTORY</div>
                <div id="liked_videos_div">LIKED_VIDEOS</div>
                <div id="chat_div">
                    <div id="friend_navbar">
                        <div>Amigos</div>
                    </div>
                    <div>
                        <div id="user_header">
                            <?php if ($GET_ALL_FRIENDS_QUERY->num_rows > 0): ?>
                                <img class="every_user_image" src="../img/profile_pic_example.jpg">
                                <div>
                                    <div>
                                        <?php
                                        echo json_decode($FRIENDS_ARRAY)[0][0];
                                        ?>
                                    </div>
                                </div>
                            <?php else: ?>
                            <?php endif; ?>
                        </div>
                        <div id="chat"></div>
                        <div style="padding: 0.7vw;">
                            <input id="input_text" type="text" placeholder="Enviar mensaje"
                                onkeypress="sendMessage(this, event)">
                        </div>
                    </div>
                </div>
                <div id="search_div">
                    <div>
                        <div>Canales</div>
                        <div id="channels_main_div"></div>
                    </div>
                    <div>
                        <div>Vídeos</div>
                        <div>
                        </div>
                    </div>
                </div>
                <div id="settings_div">
                    <div>
                        <div onclick="displaySetting('profile')">
                            General
                        </div>
                        <div onclick="displaySetting('security')">
                            Seguridad
                        </div>
                        <div onclick="displaySetting('chatterly')">
                            <img src="../img/chatterly_logo.png">Conectar con <span
                                style="color: #6458aa">Chatterly</span>©
                        </div>
                        <div onclick="displaySetting('deto')">
                            <img src="../img/deto_logo.png">Conectar con<span style="color: #229fa3">DeTo'</span>©
                        </div>
                    </div>
                    <div>
                        <div id="user_profile_pic_settings">
                            <div id="user_profile_pic"
                                style="cursor: pointer; position: relative; display: flex; align-items: center; justify-content: center">
                                <div id="cambiar_text"
                                    style="display: none; color: black; font-size: 2.5vw; position: absolute; background-color: white; width: 100%; height: 100%; align-items: center; justify-content: center; border-radius: 100%; opacity: 0.4;">
                                    Cambiar
                                </div>
                                <img class="" src="../img/profile_pic_example.jpg">
                            </div>
                            <div><?php echo $_SESSION["USERNAME"] ?></div>
                        </div>
                        <div id="security_login_settings" style="display: none; height: 100%;">
                            SECURITY
                        </div>
                        <div id="chatterly_login_settings" style="display: none; height: 100%;">
                            CHATTERLY
                        </div>
                        <div id="deto_login_settings" style="display: none; height: 100%;">
                            DETO'
                        </div>
                    </div>
                </div>
                <div>
                </div>
                <div id="channel_div">
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
    </div>

    <!-- API para iniciar sesión o registrarse -->
    <div id="mytube_login_API_wrapper" style="display: none">
        <img src="../img/x_button.png" onclick="closeLoginAPIWrapper()" />

        <div>
            <img src="../img/mytube_logo.png">
            <div>Iniciar sesión</div>
        </div>

        <form id="login_form" onsubmit="validateLoginForm(event)">
            <div>
                <label for="USERNAME">Usuario</label>
                <input type="text" name="USERNAME" pattern="[A-Za-záéíóúÁÉÍÓÚ0-9]{1,15}" required />
            </div>

            <div>
                <label for="PASSWORD">Contraseña</label>
                <input type="password" name="PASSWORD" required />
            </div>

            <div class="buttons_div">
                <a onclick="showRegisterForm()" id="create_account_button">Crear cuenta</a>
                <button type="submit" id="login_button">Iniciar sesión</button>
            </div>
        </form>

        <form id="register_form" onsubmit="validateRegisterForm(event)" style="display: none">
            <div>
                <label for="USERNAME">Usuario</label>
                <input type="text" name="USERNAME" pattern="[A-Za-záéíóúÁÉÍÓÚ0-9]{1,15}" required />
            </div>

            <div>
                <label for="EMAIL">Email</label>
                <input type="email" name="EMAIL" required />
            </div>

            <div>
                <label for="PASSWORD">Contraseña</label>
                <input type="password" name="PASSWORD" required />
            </div>

            <div class="buttons_div">
                <a onclick="showLoginForm()" id="create_account_button">Iniciar sesión</a>
                <button type="submit" id="login_button">Crear cuenta</button>
            </div>
        </form>
    </div>

    <!-- Notificaciones -->
    <div id="notifications">
    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="../js/import.js"></script>
    <script>
        setProfilePic(profile_pic);
    </script>
</body>

</html>