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
    closechat();
}

function openpendingmenu() 
{    
    pendingMenu.hidden = false;
    document.getElementById("addfriendmenu").style.display = "none";
    closechat();
}

//amigos
function fetchPendingRequests() 
{
    fetch('../php/get_pending_requests.php')
        .then(response => response.json())
        .then(data => {
            const pendingMenu = document.getElementById('pendingmenu');
            if (data.length > 0) 
            {
                pendingMenu.innerHTML = '';
                data.forEach(request => {
                    const div = document.createElement('div');
                    div.innerHTML = `
                        <p>${request.alias}</p>
                        <button onclick="manageRequest(${request.id}, 'aceptar')">Aceptar</button>
                        <button onclick="manageRequest(${request.id}, 'rechazar')">Rechazar</button>
                    `;
                    pendingMenu.appendChild(div);
                });
            } 
            else 
            {
                pendingMenu.innerHTML = '<p>No tienes solicitudes pendientes.</p>';
            }
        })
        .catch(error => console.error('Error al obtener solicitudes pendientes:', error));
}

function manageRequest(id, action) 
{
    fetch('../php/gestionar_solicitud.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `solicitante=${id}&accion=${action}`
    })
        .then(response => response.text())
        .then(message => {
            alert(message);
            fetchPendingRequests();
        })
        .catch(error => console.error('Error al gestionar la solicitud:', error));
}

function actualizarResultado(mensaje) 
{
    document.getElementById('resultado').innerText = mensaje;
}

//chat
function openchat(destinatarioID) //abrir chat
{
    destinatario = destinatarioID;  //seteamos el destinatario
    chat.style.display = "block";
    pendingMenu.hidden = true;
    document.getElementById("addfriendmenu").style.display = "none";
    initialpanel.style.display = "none";
    cargarMensajes();  //carga los mensajes
}
        
function cargarMensajes() 
{
    if (destinatario === null) return; //verifica que el destinatario este definido
    $.post('chat.php', { destinatario: destinatario }, function(data) 
    {
        try 
        {
            const mensajes = JSON.parse(data); //parsear la respuesta del servidor
            $('#chat-messages').empty();
            mensajes.forEach(function(mensaje) {
                $('#chat-messages').prepend('<div style="padding-left: 10px;"><strong>' + mensaje.alias + ':</strong> ' + mensaje.contenido + '</div>');
            });
        } 
        catch (e) 
        {
            console.error("Error al parsear JSON:", e);
            console.log("Respuesta del servidor:", data);
        }
    });
}

$('#enviarMensaje').click(function()
{
    const mensaje = $('#mensaje').val();
    if (mensaje.trim() !== '') 
    {
        $.post('chat.php', { mensaje: mensaje, destinatario: destinatario }, function() 
        {
            $('#mensaje').val(''); //limpiar el input
            cargarMensajes();
        });
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

//upload image

document.getElementById('uploadIcon').addEventListener('click', function() 
{
    document.getElementById('fileInput').click();
});

document.getElementById('fileInput').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const allowedExtensions = ['7z', 'rar', 'torrent', 'exe', 'png', 'jpg', 'jpeg', 'mp3', 'mp4', 'txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'dll', 'dat'];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (allowedExtensions.includes(fileExtension)) {
            const destinatario = document.getElementById('destinatario').value;

            if (!destinatario) {
                alert('Por favor, selecciona un destinatario antes de enviar un archivo.');
                return;
            }

            const formData = new FormData();
            formData.append('archivo', file);
            formData.append('destinatario', destinatario);

            fetch('chatterly.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Archivo enviado correctamente.');
                        console.log('Ruta del archivo:', data.ruta);
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error al enviar el archivo:', error);
                });
        } else {
            alert('Formato de archivo no permitido. Selecciona un archivo válido.');
            event.target.value = ''; // Resetea el input si el archivo no es válido
        }
    } else {
        alert('No se ha seleccionado ningún archivo.');
    }
});
