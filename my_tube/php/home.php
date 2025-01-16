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
      <div class="mytube_logo_div" onclick="location.href='home.html'">
        <img src="../img/mytube_logo.png" id="mytube_logo" />My<span style="color: red">Tube</span>
      </div>

      <input tpye="text" class="search_bar" placeholder="Buscar vídeo" />
      <div class="user_tab" onclick="displayLoginDiv()">
        <img src="../img/profile_pic.jpg" id="login_pic">
        <div>Iniciar sesión</div>
      </div>

    </div>
    <div class="nav_content_container">
      <div class="navbar">navbar</div>
      <div class="content">
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
      </div>
    </div>
  </div>

  <div class="login_div" style="display: none">
    <img class="close_img" src="../img/x_button.png" onclick="closeLoginDiv()"/>

    <form>
      <img src="../img/mytube_logo.png" id="logo">
      <div>
        <label for="alias">Usuario:</label>
        <input type="text" id="alias" name="alias" pattern="[A-Za-záéíóúÁÉÍÓÚ0-9]{3,15}" required />
      </div>

      <div>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" minlength="5" maxlength="20" required />
      </div>

      <button type="submit">Iniciar sesión</button>
      <button>Registrarse</button>
    </form>
  </div>
  <script src="../js/main_js.js"></script>
</body>

</html>