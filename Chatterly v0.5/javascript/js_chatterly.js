const normalPanel = document.getElementById("chatterly");
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
function selectFriend(nombre, foto, destinatario) 
{
    const nombreAmigo = document.getElementById('nombre-amigo'); //recoge el nombre del amigo
    const fotoAmigo = document.getElementById('foto-amigo'); //recoge la foto del amigo

    nombreAmigo.textContent = nombre; //muestra el nombre del amigo
    fotoAmigo.src = foto; //muestra la foto del amigo

    openchat(destinatario); //abre el chat con el amigo seleccionado
}

//chat
function openchat(destinatarioID) //abre el chat con el destinatario seleccionado
{
    destinatario = destinatarioID; //establece el destinatario del chat
    chat.style.display = "block";
    pendingMenu.hidden = true;
    document.getElementById("addfriendmenu").style.display = "none";
    initialpanel.style.display = "none";
    cargarMensajes();
}

//chat enter mandar mensaje
inputMensaje.addEventListener('keydown', function(event) //evento al presionar enter en el input de mensaje
{
  if (event.key === 'Enter') 
  {
    botonEnviar.click();
  }
});

$('#enviarMensaje').click(async function() //evento al enviar un mensaje
{
    const mensaje = $('#mensaje').val();
    if (mensaje.trim() !== '') 
    {
        let mensaje = $('<div>').text($('#mensaje').val()).html().trim();

        enviarMensajes_Api(id_usuario_actual, destinatario, mensaje); //envia el mensaje (chatterly)

        try //intenta enviar el mensaje a mytube
        {
            await enviarMensajes_Api(await numeroUsuario_Api(id_usuario_actual), await numeroUsuario_Api(destinatario), mensaje); //envia el mensaje (mytube)
            $('#mensaje').val(''); //limpiar el input
            cargarMensajes();
        }
        catch (e) 
        {
            console.error("No se puede conectar con mytube:", e);
        }

        $('#mensaje').val(''); //limpiar el input
        cargarMensajes();
    }
});

async function cargarMensajes() //carga los mensajes
{
    if (destinatario === null) return; //verifica si hay un destinatario seleccionado

    const imgProfileUrl = document.getElementById("profileImg2").src; //obtiene la imagen de perfil
    const fotoFriendUrl = document.getElementById("fotoFriend").src; //obtiene la imagen del amigo

        try 
        {
            let mensajes = await cargarMensajes_Api(id_usuario_actual, destinatario); //carga los mensajes
            $('#chat-messages').empty(); //limpia los mensajes
            
            mensajes.forEach(function(mensaje) //recorre los mensajes y los muestra
            {
                let fechaEnvio = mensaje.fecha_envio ? new Date(mensaje.fecha_envio).toLocaleString() : "Fecha no disponible"; //obtiene la fecha de envio 
                let imgUrl = (mensaje.id_emisor == id_usuario_actual) ? imgProfileUrl : fotoFriendUrl; //obtiene la imagen del emisor 
            
                let mensajeHtml = `<div style="padding-left: 10px; display: flex; align-items: center;">`; //crea un div para el mensaje
                mensajeHtml += `<img src="${imgUrl}" alt="Imagen de perfil" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">`; //muestra la imagen de perfil
                
                if (mensaje.tipo === 'archivo') //si el mensaje es un archivo
                {
                    const fileName = mensaje.contenido.split('/').pop(); //obtiene el nombre del archivo
                    const fileExtension = fileName.split('.').pop().toLowerCase(); //obtiene la extensión del archivo
                    let downloadLink = `<a id='link' style="text-align: center;" href="${mensaje.contenido}" download>Descargar [${fileName}]</a>`; //crea un enlace de descarga
                    
                    //muestra la imagen del archivo adjunto y el enlace de descarga debajo 
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
                else //si el mensaje es un mensaje de texto
                {
                    mensajeHtml += `<strong>${mensaje.alias}:</strong> ${mensaje.contenido}`; //muestra el mensaje
                }
                
                mensajeHtml += `<div style="font-size: 0.8em; color: #888; min-width: 110px; padding: 5px; align-items: left; margin-left: auto;">${fechaEnvio}</div>`; //muestra la fecha de envio
                mensajeHtml += '</div>';
                $('#chat-messages').prepend(mensajeHtml); //añade el mensaje al chat
            });
            
        } 
        catch (e) 
        {
            console.error("Error al parsear JSON:", e);
            console.log("Respuesta del servidor:", data);
        }
}

//enviar archivo
$('#uploadfile').click(function() //evento al hacer clic en el botón de subir archivo 
{
    document.getElementById('fileInput').click();
});

document.getElementById('fileInput').addEventListener('change', function(event) //evento al pinchar en el input para subir un archivo
{
    const file = event.target.files[0]; //obtiene el archivo seleccionado
        if (file && destinatario !== null)
        {
            //extensiones permitidas
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
        
        const fileExtension = file.name.split('.').pop().toLowerCase(); //obtiene la extensión del archivo

        if (allowedExtensions.includes(fileExtension)) //comprueba si la extensión del archivo es válida
        {
            const formData = new FormData();
            formData.append('archivo', file); //añade el archivo al formData
            formData.append('destinatario', destinatario); //añade el destinatario al formData

            fetch('uploadfiles.php', { //envia el archivo al servidor
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => { 
                if (data.success) //si el archivo se ha subido correctamente
                {
                    cargarMensajes(); //carga los mensajes
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
            alert('Formato de archivo no permitido. Selecciona un archivo valido.');
            event.target.value = ''; //limpia el input
        }
    } 
    else 
    {
        alert('No se ha seleccionado ningún archivo o no hay destinatario.');
    }
});

setInterval(cargarMensajes, 500); //cargar mensajes cada 500ms

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

function showEmojis() //muestra los emojis
{
    const emojisDiv = document.getElementById('emojisDiv');
    emojisDiv.style.display = emojisDiv.style.display === 'none' ? 'block' : 'none'; //alterna la visibilidad del emojisDiv
}

for (let category in emojis) //recorre las categorías de emojis
{
    const categoryTitle = document.createElement('div'); //crea un div para el titulo de la categoría
    categoryTitle.textContent = category;
    categoryTitle.style = "font-size: 14px; color: #fff; padding-bottom: 10px; padding-top: 10px; font-weight: bold;";
    emojiList.appendChild(categoryTitle); //añade el titulo al emojiList

   
    const emojiContainer = document.createElement('div'); //crea un div para los emojis 
    emojiContainer.style = "display: flex; flex-wrap: wrap; gap: 10px;";  

    emojis[category].forEach((emoji) => { //recorre los emojis de la categoría
        const emojiItem = document.createElement('div'); //crea un div para el emoji
        emojiItem.textContent = emoji;
        emojiItem.style = `font-size: 20px; cursor: pointer; gap: 5px; text-align: center;`;
        
        emojiItem.addEventListener('click', () => { //evento al hacer clic en el emoji
            mensajeInput.value += emoji; //añade el emoji al input
        });

        emojiContainer.appendChild(emojiItem); //añade el emoji al contenedor
    });

    emojiList.appendChild(emojiContainer); //añade el contenedor al emojiList
    
    const divisor = document.createElement('div'); //crea un divisor
    divisor.style = "height: 1px; background-color: #444; margin: 15px 0;";
    emojiList.appendChild(divisor); //añade el divisor al emojiList
}

//imagen perfil
function showprofileinfo() //muestra el panel de información del perfil
{
    showoptionspanel();
    document.getElementById("profileinfo").style.display = "block";
}

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