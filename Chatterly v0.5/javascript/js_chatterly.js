const normalPanel = document.getElementById("bienvenida");
const optionsPanel = document.getElementById("options");
const initialpanel = document.getElementById("initialpanel");
const chat = document.getElementById("chatcontainer");
const pendingMenu = document.getElementById('pendingmenu');
const inputMensaje = document.getElementById('mensaje');
const botonEnviar = document.getElementById('enviarMensaje');
const emojiList = document.getElementById('emojiList');
const mensajeInput = document.getElementById('mensaje');
let destinatario = null;

function showoptionspanel()
{
    optionsPanel.style.display = "block";
    normalPanel.style.display = "none";
}

function closeoptionspanel()
{
    normalPanel.style.display = "block";
    optionsPanel.style.display = "none";
    document.getElementById("profileinfo").style.display = "none";
    document.getElementById("openonlinemenu").style.display = "none";
}

function closechat()
{
    chat.style.display = "none";
    initialpanel.style.display = "block";
}

function openaddfriendmenu()
{
    pendingMenu.hidden = true;
    document.getElementById("addfriendmenu").style.display = "block";
    document.getElementById("openonlinemenu").style.display = "none";
    document.getElementById("allfriends").style.display = "none";
    closechat();
}

function openpendingmenu() 
{    
    pendingMenu.hidden = false;
    document.getElementById("addfriendmenu").style.display = "none";
    document.getElementById("openonlinemenu").style.display = "none";
    document.getElementById("allfriends").style.display = "none";
    closechat();
}

function openonlinemenu() 
{    
    pendingMenu.hidden = true;
    document.getElementById("openonlinemenu").style.display = "block";
    document.getElementById("addfriendmenu").style.display = "none";
    document.getElementById("profileinfo").style.display = "none";
    document.getElementById("allfriends").style.display = "none";
    closechat();
}

function openallfriends()
{
    pendingMenu.hidden = true;
    document.getElementById("openonlinemenu").style.display = "none";
    document.getElementById("addfriendmenu").style.display = "none";
    document.getElementById("profileinfo").style.display = "none";
    document.getElementById("allfriends").style.display = "block";
}

//amigos
function manageRequest(id, action) //funcion para gestionar la solicitud
{
    fetch('../php/gestionar_solicitud.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `solicitante=${id}&accion=${action}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        fetchPendingRequests();
    })
    .catch(error => console.error('Error al gestionar la solicitud:', error));
}

function actualizarResultado(mensaje) 
{
    document.getElementById('resultado').innerText = mensaje;
}

function selectFriend(nombre, foto, destinatario) 
{
    // Almacena los datos del amigo seleccionado
    const nombreAmigo = document.getElementById('nombre-amigo'); // Div donde mostrar el nombre
    const fotoAmigo = document.getElementById('foto-amigo'); // Imagen del amigo

    // Actualiza el DOM con los datos del amigo
    nombreAmigo.textContent = nombre; // Muestra el nombre del amigo
    fotoAmigo.src = foto; // Muestra la imagen del amigo

    //console.log("Amigo seleccionado: ", nombre, foto, destinatario);
    openchat(destinatario);
}

//chat
function openchat(destinatarioID) 
{
    destinatario = destinatarioID;  // Seteamos el destinatario
    chat.style.display = "block";
    pendingMenu.hidden = true;
    document.getElementById("addfriendmenu").style.display = "none";
    initialpanel.style.display = "none";
    cargarMensajes();  // Carga los mensajes
}

async function cargarMensajes() 
{
    if (destinatario === null) return; // Verifica que el destinatario esté definido

    const imgProfileUrl = document.getElementById("profileImg2").src;  // Imagen del usuario actual
    const fotoFriendUrl = document.getElementById("fotoFriend").src;  // Imagen del amigo

        try 
        {
            let mensajes = await cargarMensajes_Api(id_usuario_actual, destinatario); // Parsear la respuesta del servidor
            $('#chat-messages').empty(); // Limpiar los mensajes previos
            
            mensajes.forEach(function(mensaje) 
            {
                let fechaEnvio = mensaje.fecha_envio ? new Date(mensaje.fecha_envio).toLocaleString() : "Fecha no disponible"; 
                let imgUrl = (mensaje.id_emisor == id_usuario_actual) ? imgProfileUrl : fotoFriendUrl;
            
                let mensajeHtml = `<div style="padding-left: 10px; display: flex; align-items: center;">`;
                mensajeHtml += `<img src="${imgUrl}" alt="Imagen de perfil" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">`;
                
                if (mensaje.tipo === 'archivo') 
                {
                    // Obtener el nombre del archivo
                    const fileName = mensaje.contenido.split('/').pop();
                    const fileExtension = fileName.split('.').pop().toLowerCase();
                    let downloadLink = `<a id='link' style="text-align: center;" href="${mensaje.contenido}" download>Descargar [${fileName}]</a>`;
                    
                    // Categorías de archivos
                    if (['png', 'jpg', 'jpeg', 'webp'].includes(fileExtension)) 
                    {
                        // Mostrar la imagen y agregar el enlace de descarga debajo
                        mensajeHtml += `<div style="margin-top: 10px; display: block;">`;
                        mensajeHtml += `<img src="${mensaje.contenido}" alt="Imagen adjunta" style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">`;
                        mensajeHtml += downloadLink;
                        mensajeHtml += `</div>`;
                    }
                    else if (['pdf'].includes(fileExtension)) 
                    {
                        mensajeHtml += `<div style="margin-top: 10px; display: block;">`;
                        mensajeHtml += `<img src="../assets/placeholders/otros.png" alt="Archivo PDF adjunto" style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">`;
                        mensajeHtml += downloadLink;
                        mensajeHtml += `</div>`;
                    }
                    else if (['mp4'].includes(fileExtension)) 
                    {
                        mensajeHtml += `<div style="margin-top: 10px; display: block;">`;
                        mensajeHtml += `<img src="../assets/placeholders/video.png" alt="Archivo de video adjunto" style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">`;
                        mensajeHtml += downloadLink;
                        mensajeHtml += `</div>`;
                    }
                    else if (['mp3'].includes(fileExtension)) 
                    {
                        mensajeHtml += `<div style="margin-top: 10px; display: block;">`;
                        mensajeHtml += `<img src="../assets/placeholders/audio.png" alt="Archivo de audio adjunto" style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">`;
                        mensajeHtml += downloadLink;
                        mensajeHtml += `</div>`;
                    }
                    else if (['zip'].includes(fileExtension)) 
                    {
                        mensajeHtml += `<div style="margin-top: 10px; display: block;">`;
                        mensajeHtml += `<img src="../assets/placeholders/comprimido.png" alt="Archivo comprimido adjunto" style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">`;
                        mensajeHtml += downloadLink;
                        mensajeHtml += `</div>`;
                    }
                    else if (['exe', 'msi'].includes(fileExtension)) 
                    {
                        mensajeHtml += `<div style="margin-top: 10px; display: block;">`;
                        mensajeHtml += `<img src="../assets/placeholders/otros.png" alt="Archivo ejecutable adjunto" style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">`;
                        mensajeHtml += downloadLink;
                        mensajeHtml += `</div>`;
                    }
                    else 
                    {
                        mensajeHtml += `<div style="margin-top: 10px; display: block;">`;
                        mensajeHtml += `<img src="../assets/placeholders/otros.png" alt="Archivo adjunto" style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">`;
                        mensajeHtml += downloadLink;
                        mensajeHtml += `</div>`;
                    }
                }
                else 
                {
                    mensajeHtml += `<strong>${mensaje.alias}:</strong> ${mensaje.contenido}`;
                }
                
                mensajeHtml += `<div style="font-size: 0.8em; color: #888; min-width: 110px; padding: 5px; align-items: left; margin-left: auto;">${fechaEnvio}</div>`;
                mensajeHtml += '</div>';
                $('#chat-messages').prepend(mensajeHtml); // Añadir el mensaje al principio del chat
            });
            
        } 
        catch (e) 
        {
            console.error("Error al parsear JSON:", e);
            console.log("Respuesta del servidor:", data);
        }
    
}

$('#enviarMensaje').click(async function() 
{
    const mensaje = $('#mensaje').val();
    if (mensaje.trim() !== '') 
    {
        let mensaje = $('<div>').text($('#mensaje').val()).html().trim();

        chat_api(id_usuario_actual, destinatario, mensaje);
        await sendMessageAPI(await numeroUsuario_Api(id_usuario_actual), await numeroUsuario_Api(destinatario), mensaje);
        $('#mensaje').val(''); //limpiar el input
        cargarMensajes();
    }
});

// Enviar archivo
$('#uploadfile').click(function() //evento al hacer clic en el botón de subir archivo 
{
    document.getElementById('fileInput').click();
});

document.getElementById('fileInput').addEventListener('change', function(event) 
{
    const file = event.target.files[0];
        if (file && destinatario !== null) 
        {
            const allowedExtensions = [
                'png', 'jpg', 'jpeg', 'gif', 'webp', 'bmp', 'tiff', 'svg',
                'mp4', 'mkv', 'mov', 'avi', 'wmv', 'flv', 'webm',
                'mp3', 'wav', 'flac', 'aac', 'ogg', 'wma', 'm4a',
                'pdf', 'txt', 'rtf', 'csv',
                'doc', 'docx', 'odt', 'xls', 'xlsx', 'ods', 'ppt', 'pptx', 'odp',
                'zip', 'rar', '7z', 'tar', 'gz', 'bz2',
                'exe', 'msi', 'apk', 'dmg', 'iso',
                'html', 'css', 'js', 'php', 'py', 'java', 'c', 'cpp', 'cs', 'sh', 'bat', 'sql', 'torrent'
        ];
        
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (allowedExtensions.includes(fileExtension)) 
        {
            const formData = new FormData();
            formData.append('archivo', file);
            formData.append('destinatario', destinatario);

            fetch('uploadfiles.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) 
                {
                    cargarMensajes(); // Recargar mensajes para incluir el nuevo archivo
                } 
                else 
                {
                    alert(data.error || 'Error al subir el archivo.');
                }
            })
            .catch(error => {
                console.error('Error al enviar el archivo:', error);
            });
        } 
        else 
        {
            alert('Formato de archivo no permitido. Selecciona un archivo válido.');
            event.target.value = ''; // Resetea el input si el archivo no es válido
        }
    } 
    else 
    {
        alert('No se ha seleccionado ningún archivo o no hay destinatario.');
    }
});

setInterval(cargarMensajes, 500); //cargar mensajes cada 500ms

cargarMensajes();

//chat enter mandar mensaje
inputMensaje.addEventListener('keydown', function(event) //evento al presionar enter
{
  if (event.key === 'Enter') 
  {
    botonEnviar.click();
  }
});

//emojis
const emojis = {
    "😄 Gente": [
        "😀", "😃", "😄", "😁", "😆", "😅", "😂", "🤣", "🥲", "😊", "😇", "🙂", "🙃", "😉", "😌",
        "😍", "🥰", "😘", "😗", "😙", "😚", "😋", "😛", "😜", "😝", "🤪", "🤨", "🧐", "🤓", "😎",
        "🥸", "🤩", "🥳", "😏", "😒", "🙄", "😞", "😔", "😟", "😕", "🙁", "☹️", "😣", "😖", "😫",
        "😩", "🥺", "😢", "😭", "😤", "😠", "😡", "🤬", "🤯", "😳", "🥵", "🥶", "😱", "😨", "😰",
        "😥", "😓", "🤗", "🤔", "🤭", "🤫", "🤥", "😶", "😐", "😑", "😬", "🙄", "😯", "😦", "😧",
        "😮", "😲", "🥱", "😴", "🤤", "😪", "😵", "🤐", "🤑", "🤠", "😷", "🤒", "🤕", "🤢", "🤮",
        "🤧", "😵‍💫", "😎", "🥳"
    ],
    "🐾 Animales y naturaleza": [
        "🐶", "🐱", "🐭", "🐹", "🐰", "🦊", "🐻", "🐼", "🐨", "🐯", "🦁", "🐮", "🐷", "🐽", "🐸",
        "🐵", "🙈", "🙉", "🙊", "🐒", "🐔", "🐧", "🐦", "🐤", "🐣", "🐥", "🦆", "🦅", "🦉", "🦇",
        "🐺", "🐗", "🐴", "🦄", "🐝", "🪲", "🐛", "🦋", "🐌", "🐞", "🐜", "🪳", "🦂", "🦟", "🦗",
        "🐢", "🐍", "🦎", "🦖", "🦕", "🐙", "🦑", "🦀", "🦞", "🦐", "🦪", "🐡", "🐠", "🐟", "🐬",
        "🐳", "🐋", "🦈", "🐊", "🐅", "🐆", "🦓", "🦍", "🦧", "🦣", "🐘", "🦛", "🦏", "🐪", "🐫",
        "🦙", "🐃", "🐂", "🐄", "🐎", "🐖", "🐏", "🐑", "🦌", "🦃", "🐓", "🦤", "🦚", "🦜", "🦢",
        "🦩", "🦔", "🦦", "🦥", "🐿️", "🦨", "🦡", "🦃"
    ],
    "🍕 Comida": [
        "🍇", "🍈", "🍉", "🍊", "🍋", "🍌", "🍍", "🥭", "🍎", "🍏", "🍐", "🍑", "🍒", "🍓",
        "🫐", "🥝", "🍅", "🫒", "🥥", "🥑", "🍆", "🥔", "🥕", "🌽", "🌶️", "🫑", "🥒", "🥦", "🧄",
        "🧅", "🍄", "🥜", "🌰", "🍞", "🥐", "🥖", "🫓", "🥨", "🥯", "🥞", "🧇", "🧀", "🍖", "🍗",
        "🥩", "🥓", "🍔", "🍟", "🍕", "🌭", "🥪", "🌮", "🌯", "🫔", "🥙", "🧆", "🥗", "🥘", "🫕",
        "🍝", "🍜", "🍲", "🍛", "🍣", "🍤", "🍚", "🍙", "🍘", "🍥", "🥠", "🥮", "🍢", "🍡", "🍧",
        "🍨", "🍦", "🥧", "🧁", "🍰", "🎂", "🍮", "🍭", "🍬", "🍫", "🍿", "🧂", "🥤", "🧋", "🧃",
        "🍵", "🍶", "🍾", "🍷", "🍸", "🍹", "🍺", "🍻", "🥂", "🥃", "🫖", "🫛", "🫚"
    ],
    "⚙️ Herramientas y objetos": [
        "🪓", "🔪", "🗡️", "⚔️", "🛡️", "🔧", "🔨", "⛏️", "⚒️", "🛠️", "🪛", "🔩", "⚙️", "🗜️", "🧱",
        "🪜", "🧰", "🪠", "🔗", "⛓️", "🪝", "🧲", "🪤", "🪜", "🪦", "🛢️", "🛡️", "🔒", "🔓", "🔑",
        "🗝️", "🧨", "🪃", "📿", "💎", "🪙"
    ],
    "🚗 Transporte y vehículos": [
        "🚗", "🚕", "🚙", "🚌", "🚎", "🏎️", "🚓", "🚑", "🚒", "🚐", "🚚", "🚛", "🚜", "🛴", "🚲",
        "🛵", "🏍️", "🛺", "🚁", "✈️", "🛫", "🛬", "🛸", "🚢", "🛳️", "⛴️", "🛥️", "🚤", "🛶", "⛵"
    ],
    "🌍 Lugares y naturaleza": [
        "🏔️", "⛰️", "🗻", "🌋", "🏕️", "🏖️", "🏝️", "🏜️", "🏞️", "🏟️", "🏛️", "🏗️", "🗽", "🗿",
        "🗼", "🏰", "🏯", "🏚️", "🏠", "🏡", "🏢", "🏣", "🏤", "🏥", "🏦", "🏨", "🏩", "🏪", "🏫",
        "🏬", "🏭", "⛪", "🕌", "🛕", "🕍", "⛩️", "🕋", "⛲"
    ],
    "⚽ Deportes": [
        "⚽", "🏀", "🏈", "⚾", "🎾", "🏐", "🏉", "🥏", "🎱", "🏓", "🏸", "🥋", "🥊", "🎯", "🤿",
        "🏹", "⛷️", "🏂", "🏋️", "🏌️", "🏄", "🏊", "🚴", "🚵"
    ]
};

for (let category in emojis) // Recorrer categorías y emojis
{
    const categoryTitle = document.createElement('div'); // Crear un título de categoría
    categoryTitle.textContent = category;
    categoryTitle.style = "font-size: 14px; color: #fff; padding-bottom: 10px; padding-top: 10px; font-weight: bold;";
    emojiList.appendChild(categoryTitle);

   
    const emojiContainer = document.createElement('div');  // Crear un contenedor para los emojis de esa categoría
    emojiContainer.style = "display: flex; flex-wrap: wrap; gap: 10px;";  // Flexbox para los emojis

    emojis[category].forEach((emoji) => { // Crear los emojis de esa categoría
        const emojiItem = document.createElement('div');
        emojiItem.textContent = emoji;
        emojiItem.style = `font-size: 20px; cursor: pointer; gap: 5px; text-align: center;`;
        
        emojiItem.addEventListener('click', () => { // Agregar evento para insertar emoji en el input
            mensajeInput.value += emoji; // Agrega el emoji al input
        });

        emojiContainer.appendChild(emojiItem);
    });

    emojiList.appendChild(emojiContainer);
    
    const divisor = document.createElement('div'); // Crear un divisor entre las categorías
    divisor.style = "height: 1px; background-color: #444; margin: 15px 0;";
    emojiList.appendChild(divisor);
}

function showEmojis()
{
    const emojisDiv = document.getElementById('emojisDiv');
    emojisDiv.style.display = emojisDiv.style.display === 'none' ? 'block' : 'none';
}

function showprofileinfo()
{
    showoptionspanel();
    document.getElementById("profileinfo").style.display = "block";
}

//imagen perfil
const img = document.getElementById('profileImg');
const img2 = document.getElementById('profileImg2');
const fileProfile = document.getElementById('fotoProfile');
const uploadForm = document.getElementById('uploadForm');

//abre el selector de archivos al hacer clic en la imagen
img.addEventListener('click', () => {
    fileProfile.click();
});

//subir la imagen seleccionada al servidor
fileProfile.addEventListener('change', () => {
    const formData = new FormData(uploadForm);

    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) 
        {
            img.src = data.newImagePath; //actualiza la imagen de perfil
            img2.src = data.newImagePath;
        } 
        else 
        {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error en la subida de la imagen:', error));
});