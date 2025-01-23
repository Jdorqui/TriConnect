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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/login_api.css" />
    <link rel="stylesheet" href="../css/chat.css" />
    <script>
        let username = '<?php echo $USERNAME ?>';
        let friendsArray = JSON.parse('<?php echo $FRIENDS_ARRAY ?>');
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
                <div id="user_logged_in_tab" onclick="displayUserSettings()">
                    <img src="../img/profile_pic_example.jpg">
                    <div><?php echo $_SESSION["USERNAME"] ?></div>
                </div>
            <?php else: ?>
                <div id="user_logged_out_tab" onclick="displayLoginAPIWrapper()">
                    <img src="../img/logged_out_profile_pic.jpg">
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
                <img src="../img/chat_icon.png" onclick="display('chat')">
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
                            <img src="../img/profile_pic_example.jpg">
                            <div>
                                TEST
                            </div>
                        </div>
                        <div id="chat" style="flex-grow: 1; overflow: scroll;"></div>
                        <div style="">
                            <input id="input_text" type="text" placeholder="Enviar mensaje"
                                onkeypress="sendMessage(this, event)"
                                style="width: 100%; box-sizing: border-box; padding: 10px; margin: 0">
                        </div>
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

    <script src="../js/main.js"></script>
    <script src="../js/login_api.js"></script>
    <script src="../js/chat.js"></script>
</body>

</html>