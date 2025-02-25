<?php if (true): ?>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <style>
        #mytube_login_API_wrapper * {
            box-sizing: border-box;
            font-family: "Montserrat", serif;
            font-optical-sizing: auto;
            font-weight: 600;
            font-style: normal;

            user-select: none;
        }

        /* div iniciar sesión. */
        #mytube_login_API_wrapper {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            margin: auto;

            color: white;

            background-color: rgb(12, 12, 12);
            border: 0.1vw solid white;
            border-radius: 2vw;

            width: 60%;
            height: 50%;

            display: flex;
        }

        /* Imagen x_button.png. */
        #mytube_login_API_wrapper>img {
            position: absolute;
            right: 2%;
            top: 2%;
            width: 3%;
            z-index: 1;
            cursor: pointer;
        }

        /* div del logo con texto. */
        #mytube_login_API_wrapper>:nth-child(2) {
            display: flex;
            flex-direction: column;

            width: 40%;
            height: 100%;

            justify-content: center;
            align-items: center;

            border-radius: 20px 0 0 20px;
            border-right: 0.1vw white solid;
        }

        /* Logo MyTube dentro de la API. */
        #mytube_login_API_wrapper>:nth-child(2)>img {
            display: block;
            height: 40%;
        }

        /* div del texto. */
        #mytube_login_API_wrapper>:nth-child(2)>div {
            position: absolute;
            bottom: 10%;
            font-size: 1.75vw;
        }

        /* forms para iniciar sesión y registrarse. */
        #mytube_login_API_wrapper form {
            display: flex;
            flex-direction: column;
            flex-grow: 1;

            height: 100%;

            justify-content: center;
            align-items: center;

            font-size: 1.5vw;
        }

        /* Todos los elementos excepto el div de los botones. */
        #mytube_login_API_wrapper form>:not(.buttons_div) {
            width: 70%;
            margin-bottom: 1vw;
        }

        /* inputs. */
        #mytube_login_API_wrapper form input {
            width: 100%;

            font-size: 1.5vw;

            box-sizing: border-box;

            background-color: black;
            border: 0.1vw solid white;
            border-radius: 1vw;
            color: white;

            padding: 0.5vw;
        }

        /* div de los botones. */
        .buttons_div {
            position: absolute;

            right: 2%;
            bottom: 5%;

            font-size: 1.5vw;
        }

        .buttons_div a {
            margin-right: 1vw;

            cursor: pointer;
        }

        .buttons_div button {
            font-size: 1.5vw;
            background-color: transparent;
            color: white;
            width: fit-content !important;

            cursor: pointer;
        }

        .buttons_div a:hover {
            text-decoration-line: underline;
        }

        #next_button,
        #login_button {
            padding: 0.5vw;

            border: 0.1vw solid white;
            border-radius: 1vw;

            transition: background-color 0.2s;
        }

        #login_button:hover {
            background-color: red;
        }

        #create_account_button {
            border: 0
        }
    </style>
    <div id="mytube_login_API_wrapper">
        <img id="x_button" onclick="closeLoginAPIWrapper()" />

        <div>
            <img id="mytube_logo">
            <div>Iniciar sesión</div>
        </div>

        <form id="login_form">
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
<?php endif; ?>