<?php
session_start(); // Iniciamos la sesión

if (!isset($_SESSION['usuario']) || !isset($_SESSION['password']))  // Si el usuario no ha iniciado sesión
{ 
    header("Location: index.html"); // Redirecciona al index
    exit();
}

// Recupera el usuario y contraseña de la sesión
$usuario = $_SESSION['usuario'];
$password = $_SESSION['password'];


try // Conectar a la base de datos
{
    $pdo = new PDO('mysql:host=localhost;dbname=chatterly', 'root', ''); // Ajusta los parámetros de conexión
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) 
{
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

// Obtener el ID del usuario
$stmt = $pdo->prepare("SELECT id_user FROM usuarios WHERE username = ?");
$stmt->execute([$usuario]);
$usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuarioData) 
{
    echo "Usuario no encontrado.";
    exit();
}
$id_usuario_actual = $usuarioData['id_user'];

// Obtener solicitudes pendientes
$stmt = $pdo->prepare("SELECT usuarios.alias, amigos.id_user1 FROM amigos 
                       JOIN usuarios ON amigos.id_user1 = usuarios.id_user 
                       WHERE amigos.id_user2 = ? AND amigos.estado = 'pendiente'");
$stmt->execute([$id_usuario_actual]);
$solicitudes_pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener la lista de amigos del usuario
$stmt = $pdo->prepare("
    SELECT usuarios.alias, 
           usuarios.profile_picture, 
           amigos.id_user1 
    FROM amigos 
    JOIN usuarios ON amigos.id_user1 = usuarios.id_user OR amigos.id_user2 = usuarios.id_user 
    WHERE (amigos.id_user1 = :id_usuario OR amigos.id_user2 = :id_usuario) 
    AND amigos.estado = 'aceptado' 
    AND usuarios.id_user != :id_usuario
");
$stmt->execute(['id_usuario' => $id_usuario_actual]);
$amigos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chatterly</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../assets/imgs/logo_bg.ico">
    </head>
    <body>
        <div id="bienvenida">
            <div style="display: flex; flex-direction: column; width: 100vw; height: 100vh;">
                <div style="background-color: #1e1f22; color: white; padding: 5px; text-align: left; height: 20px; font-size: 15px"> <!-- superior -->
                    Chatterly
                </div>
                <div style="display: flex; flex: 1;">
                    <div style="background-color: #1e1f22; width: 2.7vw; padding: 10px; color: white; min-width: 50px;"> <!-- barra1 -->
                        <div>
                            <img id="message" src="../assets/imgs/message_logo.png" alt="logo" onclick=""><br>
                            <div style="height: 2px; background-color:rgb(57, 62, 66)"></div><br>
                            <img id="message" src="../assets/imgs/newServer_logo.png" alt="logo" onclick="" style="padding: 10px; width: 30px; height: 30px;"><br>
                        </div>
                    </div>
                    <div style="background-color: #2b2d31; width: 10%; padding: 10px; color: white; min-width: 170px;"> <!-- barra2 -->
                        <div style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                            <div>
                                <button id="options-button" style="text-align: center; display: flex; align-items: center; onclick="closechat()">
                                    <img src="../assets/imgs/friends_logo.png" alt="account" style="width: 20px; height: 20px; margin-right: 15px;">
                                    Amigos
                                </button>
                                <div style="height: 2px; background-color:rgb(57, 62, 66)"></div>
                                <p style="text-align: center;">MENSAJES DIRECTOS</p>
                                <?php
                                if (count($amigos) > 0) 
                                {
                                    foreach ($amigos as $amigo) 
                                    {
                                        $foto = $amigo['profile_picture'] ?? '../assets/imgs/default_profile.png';
                                        echo "
                                            <button onclick='openchat()' id='options-button' style='display: flex; align-items: center; gap: 10px; border: none; padding: 10px; border-radius: 5px; margin-bottom: 5px; cursor: pointer; width: 100%; text-align: left;'>
                                                <img src='$foto' alt='Foto de perfil' style='width: 30px; height: 30px; border-radius: 50%;'>
                                                <span>{$amigo['alias']}</span>
                                            </button>
                                        ";
                                    }
                                } 
                                else 
                                {
                                    echo "<p>No tienes amigos en la lista.</p>";
                                }
                                ?>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;  background-color: #232428; border-radius: 10px; width: 100%; "> <!-- userpanel -->
                                
                                <img src="../assets/imgs/bg.png" alt="profile" style="border-radius: 50%; width: 40px; height: 40px;"> <!-- img -->
                                
                                <span style="color: white; font-size: 16px; font-weight: bold;"><?php echo htmlspecialchars($usuario); ?></span> <!-- username -->
                                
                                <div style="display: flex; gap: 10px; margin-left: auto;"> <!-- iconos -->
                                    <img src="../assets/imgs/microphone_icon.png" alt="microphone" style="width: 24px; height: 24px; cursor: pointer;">
                                    <img src="../assets/imgs/headphone_icon.png" alt="headphones" style="width: 24px; height: 24px; cursor: pointer;">
                                    <img src="../assets/imgs/options_icon.png" alt="options" style="width: 24px; height: 24px; cursor: pointer;" onclick="showoptionspanel()">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="initialpanel" style="background-color: #313338; flex: 1; display: flex; flex-direction: column; min-width: 200px;"> <!-- initialpanel -->
                        <div style="background-color: #313338; display: flex; padding: 10px; align-items: center; color: white; gap: 10px;"> 
                            <img src="../assets/imgs/friends_logo.png" alt="friends" style="padding: 10px; width: 24px; height: 24px;">
                            <span style="font-size: 16px;">Amigos</span>
                            <div style="width: 2px; background-color:rgb(57, 62, 66); height: 100%;"></div>
                            <button class="friend-tab-button" style="width: 60px;">En linea</button>
                            <button class="friend-tab-button" style="width: 50px;">Todos</button>
                            <button class="friend-tab-button" onclick="openpendingmenu()">Pendiente</button>
                            <button class="friend-tab-button">Bloqueado</button>
                            <button class="add-friend-button" onclick="openaddfriendmenu()">Añadir amigo</button>
                        </div>
                        
                        <div id="addfriendmenu" style="display: column;  padding: 30px;" hidden>
                                <span>AÑADIR AMIGO</span>
                                <p>Puedes añadir amigos con su nombre de usuario de Chatterly.</p>
                            <div style="display: flex; overflow: hidden; background-color: #313338;">
                                <form action="../php/enviar_solicitud.php" method="post" style="width: 100%; position: relative;">
                                    <input id="alias_amigo" name="alias_amigo" required type="text" style="border-color: #1e1f22; background-color: #1e1f22; width: 100%; box-sizing: border-box; height: 50px; padding-left: 10px; position: relative;" placeholder="Puedes añadir amigos con su nombre de usuario de Chatterly.">
                                    <button id="enviar_solicitud" type="submit" style="width: 200px;position: absolute; right: 5px; top: 5px; height: 40px; font-size: 14px; background-color: #5865F2; cursor: pointer; padding: 0 15px; border: none;">Enviar solicitud de amistad</button>
                                </form>
                            </div>
                            <p id="resultado"></p>
                        </div>

                        <div id="pendingmenu" style="padding: 30px;" hidden>
                            <h3>Solicitudes Pendientes</h3>
                            <?php
                            if (isset($solicitudes_pendientes) && count($solicitudes_pendientes) > 0) 
                            {
                                //comprueba si hay solicitudes penddientes
                                foreach ($solicitudes_pendientes as $solicitud): ?> 
                                    <div class="solicitud">
                                        <span><?php echo htmlspecialchars($solicitud['alias']); ?></span>
                                        <form action="gestionar_solicitud.php" method="post">
                                            <input type="hidden" name="solicitante" value="<?php echo $solicitud['id_user1']; ?>">
                                            <button class="" type="submit" name="accion" value="aceptar">Aceptar</button>
                                            <button type="submit" name="accion" value="rechazar">Rechazar</button>
                                        </form>
                                    </div>
                                <?php endforeach;
                            } 
                            else 
                            {
                                echo "<p>No hay solicitudes pendientes.</p>"; //si no hay solicitudes pendientes
                            }
                            ?>
                        </div>

                        <div id="chat" hidden>
                            <div id="chat" style="flex: 1; padding: 10px; color: white;"> <!-- chat -->
                            </div>
                            <div style="padding: 10px; overflow: hidden; background-color: #313338;"> <!-- barra chat -->
                                <input type="text" style="border-color: #383a40; background-color: #383a40; width: 100%; box-sizing: border-box; height: 30px; padding: 0px; padding-left: 10px;" placeholder="Escribe un mensaje a...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="options" hidden>
            <div style="display: flex; flex-direction: column;  width: 100vw; height: 100vh;">
                <div style="background-color: #1e1f22; color: white; text-align: left; height: 30px;"> <!-- superior -->
                    Chatterly
                </div>
                <div style="display: flex; flex: 1;">
                    <div style="background-color: #2b2d31; width: 30%; padding: 10px; color: white; display: flex; flex-direction: column; gap: 10px;"> <!-- barra1 -->
                        <div class="form-container" style="align-items: center;">
                            <p style="text-align: center;">AJUSTES DE USUARIO</p>
                            <button id="options-button" style="text-align: center;">Mi cuenta</button>
                            <button id="options-button" style="text-align: center;">Perfiles</button>
                            <button id="options-button" style="text-align: center;">Dispositivos</button>
                            <button id="options-button" style="text-align: center;">Conexiones</button>
                        </div>
                        <div style="height: 2px; background-color:rgb(57, 62, 66)"></div>
                        <div class="form-container" style="align-items</div>: center;">
                            <p id="options-title" style="text-align: center;">AJUSTES DE LA APLICACION</p>
                            <button id="options-button" style="text-align: center;">Apariencia</button>
                            <button id="options-button" style="text-align: center;">Accesibilidad</button>
                            <button id="options-button" style="text-align: center;">Voz y Video</button>
                            <button id="options-button" style="text-align: center;">Chat</button>
                            <button id="options-button" style="text-align: center;">Notificaciones</button>
                            <button id="options-button" style="text-align: center;">Atajos de teclado</button>
                        </div>
                        <div style="height: 2px; background-color:rgb(57, 62, 66)"></div>
                            <div class="form-container" style="align-items: center;">
                            <button id="options-button" style="text-align: center;" onclick="window.location.href='../php/logout.php'">Cerrar sesión</button>
                            <button id="options-button" style="text-align: center;" onclick="closeoptionspanel()">Volver</button>
                        </div>
                    </div>
                        <div style="background-color: #313338; width: 100%; padding: 10px; color: white;"> <!-- barra2 -->
                    </div>
                </div>
            </div>
        </div>
        <script defer src="../javascript/js_bienvenida.js"></script>
    </body>
</html>