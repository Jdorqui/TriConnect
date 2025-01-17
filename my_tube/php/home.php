<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>
  <link rel="stylesheet" href="../css/main_css.css" />
  <link rel="stylesheet" href="../css/home.css" />
  <link rel="icon" type="image/x-icon" href="../img/mytube_logo.png" />
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
      <div class="content">
        <button onclick="location.href='logout.php'">CERRAR SESIÓN</button>
        <!--
        <div class="recoms">
          <div class="recom">
            <img src="../img/thumbnail.webp" class="thumbnail">
            <div class="recom_title">oasdosadnosaodasoid</div>
            <div class="user">
              <img class="profile_pic" src="../img/profile_pic.jpg">
              <span class="user_stats">
                <div class="username">USERNAME</div>
                <div class="user_subs">1M suscriptores</div>
              </span>
            </div>
          </div>
          <div class="recom">
            <img src="../img/thumbnail.webp" class="thumbnail">
          </div>
          <div class="recom">
            <img src="../img/thumbnail.webp" class="thumbnail">
          </div>
          <div class="recom">
            <img src="../img/thumbnail.webp" class="thumbnail">
          </div>
          <div class="recom">
            <img src="../img/thumbnail.webp" class="thumbnail">
          </div>
          <div class="recom">
            <img src="../img/thumbnail.webp" class="thumbnail">
          </div>
        </div>
-->
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
              <input type="text" id="USERNAME" name="USERNAME" pattern="[A-Za-záéíóúÁÉÍÓÚ0-9]{1,15}" placeholder="..."
                required />
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
              <input type="text" id="USERNAME" name="USERNAME" pattern="[A-Za-záéíóúÁÉÍÓÚ0-9]{1,15}" placeholder="..."
                required />
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
</body>

</html>