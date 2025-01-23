<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/login_api.css" />
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
                <div class="user_logged_in_tab" onclick="displayUserSettings()">
                    <img src="../img/profile_pic_example.jpg">
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
                <div id="chat_div">CHAT</div>
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

    <script src="../js/main.js"></script>
    <script src="../js/login_api.js"></script>
</body>

</html>