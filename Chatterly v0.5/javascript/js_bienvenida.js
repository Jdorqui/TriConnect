const normalPanel = document.getElementById("bienvenida");
const optionsPanel = document.getElementById("options");
const initialpanel = document.getElementById("initialpanel");
const chat = document.getElementById("chatcontainer");
const pendingMenu = document.getElementById('pendingmenu');
let destinatario = null;  // Asigna el ID del usuario con quien estás chateando.
//chat.style.display === "none";

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

function openchat()
{
    chat.style.display = "block";
    //chat.hidden = false;
    pendingMenu.hidden = true;
    document.getElementById("addfriendmenu").style.display = "none";
    initialpanel.style.display = "none";
    if(document.getElementById("nombreboton").innerHTML == "a")
        {
            
            destinatario = 1;
            console.log(destinatario);
        }
    else
        {
            
            destinatario = 3;
            console.log(destinatario);
        }
}

function closechat()
{
    chat.style.display = "none";
    //chat.hidden = true;
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


        
// Función para cargar los mensajes
function cargarMensajes() {
   $.post('chat.php', { destinatario: destinatario }, function(data) {
try {
const mensajes = JSON.parse(data); // Intentamos parsear la respuesta JSON
$('#chat-messages').empty();
mensajes.forEach(function(mensaje) {
    $('#chat-messages').prepend('<div><strong>' + mensaje.alias + ':</strong> ' + mensaje.contenido + '</div>');
});
} catch (e) {
console.error("Error al parsear JSON:", e);
console.log("Respuesta del servidor:", data); // Muestra la respuesta del servidor para depurar
}
});
}

// Enviar mensaje
$('#enviarMensaje').click(function() {
    const mensaje = $('#mensaje').val();
    if (mensaje.trim() !== '') {
        $.post('chat.php', { mensaje: mensaje, destinatario: destinatario }, function() {
            $('#mensaje').val('');
            cargarMensajes(); // Cargar los mensajes actualizados
        });
    }
});

// Cargar mensajes cada 2 segundos para mantener el chat actualizado
setInterval(cargarMensajes, 2000);

// Inicializar el chat cargando los mensajes al principio
cargarMensajes();