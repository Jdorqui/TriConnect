const normalPanel = document.getElementById("bienvenida");
const optionsPanel = document.getElementById("options");
const initialpanel = document.getElementById("initialpanel");
const chat = document.getElementById("chatcontainer");
const pendingMenu = document.getElementById('pendingmenu');
const inputMensaje = document.getElementById('mensaje');
const botonEnviar = document.getElementById('enviarMensaje');
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
                $('#chat-messages').prepend('<div><strong>' + mensaje.alias + ':</strong> ' + mensaje.contenido + '</div>');
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
// Emojis organizados por categorías
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
    "🍕 Comida y bebida": [
        "🍇", "🍈", "🍉", "🍊", "🍋", "🍌", "🍍", "🥭", "🍎", "🍏", "🍐", "🍑", "🍒", "🍓",
        "🫐", "🥝", "🍅", "🫒", "🥥", "🥑", "🍆", "🥔", "🥕", "🌽", "🌶️", "🫑", "🥒", "🥦", "🧄",
        "🧅", "🍄", "🥜", "🌰", "🍞", "🥐", "🥖", "🫓", "🥨", "🥯", "🥞", "🧇", "🧀", "🍖", "🍗",
        "🥩", "🥓", "🍔", "🍟", "🍕", "🌭", "🥪", "🌮", "🌯", "🫔", "🥙", "🧆", "🥗", "🥘", "🫕",
        "🍝", "🍜", "🍲", "🍛", "🍣", "🍤", "🍚", "🍙", "🍘", "🍥", "🥠", "🥮", "🍢", "🍡", "🍧",
        "🍨", "🍦", "🥧", "🧁", "🍰", "🎂", "🍮", "🍭", "🍬", "🍫", "🍿", "🧂", "🥤", "🧋", "🧃",
        "🍵", "🍶", "🍾", "🍷", "🍸", "🍹", "🍺", "🍻", "🥂", "🥃"
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




const emojiList = document.getElementById('emojiList');
const mensajeInput = document.getElementById('mensaje');

// Recorrer categorías y emojis
for (let category in emojis) 
{
    // Crear un título de categoría
    const categoryTitle = document.createElement('div');
    categoryTitle.textContent = category;
    categoryTitle.style = "font-size: 14px; color: #fff; padding-bottom: 10px; padding-top: 10px; font-weight: bold;";
    emojiList.appendChild(categoryTitle);

    // Crear un contenedor para los emojis de esa categoría
    const emojiContainer = document.createElement('div');
    emojiContainer.style = "display: flex; flex-wrap: wrap; gap: 10px;";  // Flexbox para los emojis

    // Crear los emojis de esa categoría
    emojis[category].forEach((emoji) => {
        const emojiItem = document.createElement('div');
        emojiItem.textContent = emoji;
        emojiItem.style = `font-size: 20px; cursor: pointer; gap: 5px; text-align: center;`;
        
        // Agregar evento para insertar emoji en el input
        emojiItem.addEventListener('click', () => {
            mensajeInput.value += emoji; // Agrega el emoji al input
        });

        emojiContainer.appendChild(emojiItem);
    });

    emojiList.appendChild(emojiContainer);

    // Crear un divisor entre las categorías
    const divisor = document.createElement('div');
    divisor.style = "height: 1px; background-color: #444; margin: 15px 0;";
    emojiList.appendChild(divisor);
}

function showEmojis()
{
    const emojisDiv = document.getElementById('emojisDiv');
    emojisDiv.style.display = emojisDiv.style.display === 'none' ? 'block' : 'none';
}