<?php
// error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);
session_start();

$USERNAME = $_SESSION['USERNAME'];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $CHANNEL_ID = $_GET['channel_id'];

    $SERVER_NAME = "localhost";
    $SERVER_USERNAME = "root";
    $SERVER_PASSWORD = "root";
    $DATABASE_NAME = "MYTUBE";

    try {
        $CONN = new mysqli($SERVER_NAME, $SERVER_USERNAME, $SERVER_PASSWORD, $DATABASE_NAME);
    } catch (Exception $e) {
        header('Location: home.php');
    }

    $CHECK_EXISTING_USER_QUERY = $CONN->query("SELECT '1' FROM USERS WHERE USERNAME = '$CHANNEL_ID'");
    if ($CHECK_EXISTING_USER_QUERY->num_rows == 0) {
        header('Location: home.php');
    }

    $CHECK_USER_SUBSCRIBED_QUERY = $CONN->query("SELECT '1' FROM SUBS WHERE USERNAME = '$USERNAME' AND SUBSCRIBED_TO = '$CHANNEL_ID'");
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
                        <div style="flex-grow: 1; display:flex; flex-direction: column; padding: 10px;">
                            <?php
                            echo $CHANNEL_ID;
                            ?>
                            <div style="display:flex; flex-grow:1">
                                <?php if ($CHECK_USER_SUBSCRIBED_QUERY->num_rows == 0): ?>
                                    <div class="subscribe_button" onclick="subscribe(username, channelID)"
                                        id="subscription">Suscribirse</div>
                                <?php else: ?>
                                    <div class="subscribe_button" onclick="unsubscribe(username, channelID)"
                                        id="subscription">Suscrito</div>
                                    <div id="chat_button">Enviar mensaje</div>
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


                <!-- <button onclick="location.href='logout.php'">CERRAR SESIÓN</button> -->


            </div>
        </div>
    </div>

    <div id="mytube_login_API_wrapper" style="display: none">
        <img class="close_img" src="../img/x_button.png" onclick="closeLoginAPIWrapper()" />

        <div id="login_div">
            <div class="section_1">
                <img src="../img/mytube_logo.png" id="logo">
                <div>Iniciar sesión</div>
            </div>

            <div class="section_2">
                <form id="login_form" onsubmit="validateLoginForm(event)">
                    <div id="user_div">
                        <label for="USERNAME">Usuario</label>
                        <div>
                            <input type="text" id="USERNAME" name="USERNAME" pattern="[A-Za-záéíóúÁÉÍÓÚ0-9]{1,15}"
                                placeholder="..." required />
                        </div>
                    </div>

                    <div id="password_div">
                        <label for="PASSWORD">Contraseña</label>
                        <div>
                            <input type="password" id="PASSWORD" name="PASSWORD" required />
                        </div>
                    </div>

                    <div class="buttons_div">
                        <a onclick="showRegisterDiv()" id="create_account_button">Crear cuenta</a>
                        <button type="submit" id="login_button">Iniciar sesión</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="register_div" style="display: none">
            <div class="section_1">
                <img src="../img/mytube_logo.png" id="logo">
                <div>Crear cuenta</div>
            </div>

            <div class="section_2">
                <form id="register_form" onsubmit="validateRegisterForm(event)">
                    <div>
                        <label for="USERNAME">Usuario</label>
                        <div>
                            <input type="text" id="USERNAME" name="USERNAME" pattern="[A-Za-záéíóúÁÉÍÓÚ0-9]{1,15}"
                                placeholder="..." required />
                        </div>
                    </div>

                    <div>
                        <label for="EMAIL">Email</label>
                        <div>
                            <input type="email" id="EMAIL" name="EMAIL" required />
                        </div>
                    </div>

                    <div>
                        <label for="PASSWORD">Contraseña</label>
                        <div>
                            <input type="password" id="PASSWORD" name="PASSWORD" required />
                        </div>
                    </div>

                    <div class="buttons_div">
                        <a onclick="showLoginDiv()" id="create_account_button">Iniciar sesión</a>
                        <button type="submit" id="login_button">Crear cuenta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="notifications">
    </div>

    <script src="../js/main_js.js"></script>
    <script src="../js/channel_js.js"></script>
</body>

</html>