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
           amigos.id_user1,
           amigos.id_user2
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
                <div style="background-color: #1e1f22; color: white; padding: 5px; text-align: left; height: 20px;"> <!-- superior -->
                    <span style="font-weight: bold; color: #949ba4;">Chatterly</span>
                </div>
                <div style="display: flex; flex: 1;">
                    <div style="background-color: #1e1f22; width: 2.7vw; padding: 10px; color: white; min-width: 50px;"> <!-- barra1 -->
                        <div>
                            <img id="message-logo" src="../assets/imgs/message_logo.png" alt="logo" onclick=""><br>
                            <div style="height: 2px; background-color:rgb(57, 62, 66)"></div><br>
                            <img id="message" src="../assets/imgs/newServer_logo.png" alt="logo" onclick="" style="padding: 10px; width: 30px; height: 30px;"><br>
                        </div>
                    </div>
                    <div style="background-color: #2b2d31; width: 11%; color: white; min-width: 200px;"> <!-- barra2 -->
                        <div style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                            <div style="padding: 10px;">
                                <button id="options-button" style="text-align: center; display: flex; align-items: center;" onclick="closechat();">
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
                                        $destinatario = ($amigo['id_user1'] == $id_usuario_actual) ? $amigo['id_user2'] : $amigo['id_user1'];
                                        echo "
                                            <button onclick='openchat($destinatario)' id='options-button' style='display: flex; align-items: center; gap: 10px; border: none; padding: 10px; border-radius: 5px; margin-bottom: 5px; cursor: pointer; width: 100%; text-align: left;'>
                                                <img src='$foto' alt='Foto de perfil' style='width: 30px; height: 30px; border-radius: 50%;'>
                                                <span id='nombreboton'>{$amigo['alias']}</span>
                                            </button>
                                        ";
                                    }
                                } 
                                else 
                                {
                                    echo "<p style='text-align: center;'>No tienes amigos en la lista</p>";
                                }
                                ?>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background-color: #232428; width: 100%;"> <!-- userpanel -->
                                <?php
                                     $foto = $amigo['profile_picture'] ?? '../assets/imgs/default_profile.png';
                                     echo"<button class='kk' id='kk'> <img src='$foto' alt='profile' style='border-radius: 50%; width: 30px; height: 30px;'> <span style='color: white; font-size: 16px; font-weight: bold;'>$usuario</span> </button>";
                                ?>
                                
                                
                                
                                
                                <div style="display: flex; gap: 10px; margin-left: 60px;"> <!-- iconos -->
                                    <img src="../assets/imgs/microphone_icon.png" alt="microphone" style="width: 15px; height: 15px; cursor: pointer;">
                                    <img src="../assets/imgs/headphone_icon.png" alt="headphones" style="width: 15px; height: 15px; cursor: pointer;">
                                    <img src="../assets/imgs/options_icon.png" alt="options" style="width: 15px; height: 15px; cursor: pointer;" onclick="showoptionspanel()">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="initialpanel" style="background-color: #313338; flex: 1; display: flex; flex-direction: column; min-width: 500px;"> <!-- initialpanel -->
                        <div style="background-color: #313338; display: flex; padding: 10px; align-items: center; color: white; gap: 10px;"> 
                            <img src="../assets/imgs/friends_logo.png" alt="friends" style="padding: 10px; width: 24px; height: 24px;">
                            <span style="font-size: 16px;">Amigos</span>
                            <div id="divisor" style="width: 2px; background-color:rgb(57, 62, 66); height: 100%;"></div>
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

                        <div id="pendingmenu" style="padding: 30px; padding-top: 0;" hidden>
                            <p style="font-size: 20px;">Solicitudes Pendientes</p>
                            <?php
                            if (isset($solicitudes_pendientes) && count($solicitudes_pendientes) > 0) 
                            {
                                //comprueba si hay solicitudes penddientes
                                foreach ($solicitudes_pendientes as $solicitud): ?> 
                                    <div class="solicitud" style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background-color: #2b2d31; margin-bottom: 10px; border-radius: 5px;">
                                        <span><?php echo htmlspecialchars($solicitud['alias']) . " quiere ser tu amigo."; ?></span>
                                        <form action="gestionar_solicitud.php" method="post" style="display: flex; gap: 10px;">
                                            <input type="hidden" name="solicitante" value="<?php echo $solicitud['id_user1']; ?>">
                                            <button type="submit" name="accion" value="aceptar" style="background-color: #5865F2; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Aceptar</button>
                                            <button type="submit" name="accion" value="rechazar" style="background-color: #FF5C5C; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Rechazar</button>
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
                    </div>
                    
                    <div id="chatcontainer" style="display: none; flex: 1; flex-direction: column; min-width: 200px; background-color: #313338;">
                        
                        <div id="chat-messages" style="flex: 1; overflow-y: auto; display: flex; flex-direction: column-reverse; gap: 10px; height: 100%; max-height: calc(100vh - 100px);"></div>

                        <div style="padding: 10px; display: flex; position: relative; overflow: hidden;"> 
                            <input type="text" id="mensaje" style="border-color:#383a40; background-color: #383a40; width: 100%; box-sizing: border-box; height: 45px; padding-left: 10px; position: relative;" placeholder="Escribe un mensaje..." />
                            <img src="../assets/imgs/upload.png" style="width: 35px; position: absolute; right: 5px; top: 15px; height: 35px; cursor: pointer; padding: 0 15px; border: none;" id="uploadIcon" alt="Upload">
                            <input type="file" id="fileInput" style="display: none;">
                            <input type="hidden" id="destinatario">
                            <img src="../assets/imgs/emojis.png" onclick="showEmojis()" style="width: 40px; position: absolute; right: 43px; top: 12px; height: 40px; cursor: pointer; padding: 0 15px; border: none;">
                            <img src="../assets/imgs/gif.png" style="width: 37px; position: absolute; right: 85px; top: 17px; height: 32px; cursor: pointer; padding: 0 15px; border: none;">
                            <button id="enviarMensaje" style="width: 100px; position: absolute; right: 800%; top: 15px; height: 20px; background-color: #5865F2; cursor: pointer; padding: 0 15px; border: none;">Enviar</button>
                        </div>

                        <div style="position: relative;">
                            <div id="emojisDiv" style="display: none; position: absolute; right: 10px; bottom: 60px; width: 350px; max-height: 400px; overflow-y: auto; background: #2b2d31; border-color: #383a3f; border-style: solid; border-radius: 10px; padding: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); z-index: 1000;">
                                <div id="emojiList" style="display: flex; flex-direction: column; gap: 10px;"></div>
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
                    <div style="background-color: #313338; width: 100%; padding: 10px; color: white;"> <!-- barra3 -->
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script defer src="../javascript/js_bienvenida.js"></script>
    </body>
</html>