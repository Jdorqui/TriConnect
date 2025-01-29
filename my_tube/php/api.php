<?php 
echo '

<link rel="stylesheet" href="../css/login_api.css" />
<script type="text/javascript" src="../js/login_api.js"></script>

<div id="mytube_login_API_wrapper">
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
</div>';

echo 'asdasdasd';