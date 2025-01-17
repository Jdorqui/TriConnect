const normalPanel = document.getElementById("bienvenida");
const optionsPanel = document.getElementById("options");
const initialpanel = document.getElementById("initialpanel");
const chat = document.getElementById("chatcontainer");
const pendingMenu = document.getElementById('pendingmenu');
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
function openchat(destinatarioID) // Función para abrir el chat y configurar el destinatario
{
    destinatario = destinatarioID;  // Establecemos el destinatario dinámicamente
    chat.style.display = "block";
    pendingMenu.hidden = true;
    document.getElementById("addfriendmenu").style.display = "none";
    initialpanel.style.display = "none";
    
    //console.log("Destinatario: ", destinatario);
    cargarMensajes();  // Cargar los mensajes de inmediato cuando se abre el chat
}
        
function cargarMensajes() 
{
    if (destinatario === null) return; // Verifica que el destinatario esté definido
    $.post('chat.php', { destinatario: destinatario }, function(data) 
    {
        try 
        {
            const mensajes = JSON.parse(data); // Intentamos parsear la respuesta JSON
            $('#chat-messages').empty();
            mensajes.forEach(function(mensaje) {
                $('#chat-messages').prepend('<div><strong>' + mensaje.alias + ':</strong> ' + mensaje.contenido + '</div>');
            });
        } 
        catch (e) 
        {
            console.error("Error al parsear JSON:", e);
            console.log("Respuesta del servidor:", data); // Muestra la respuesta del servidor para depurar
        }
    });
}

$('#enviarMensaje').click(function()
{
    const mensaje = $('#mensaje').val();
    if (mensaje.trim() !== '') 
    {
        $.post('chat.php', { mensaje: mensaje, destinatario: destinatario }, function() {
            $('#mensaje').val('');  // Limpiar el campo de entrada
            cargarMensajes(); // Cargar los mensajes actualizados
        });
    }
});

// Cargar mensajes cada 2 segundos para mantener el chat actualizado
setInterval(cargarMensajes, 500);

// Inicializar el chat cargando los mensajes al principio
cargarMensajes();

  // Seleccionamos el input y el botón
  const inputMensaje = document.getElementById('mensaje');
  const botonEnviar = document.getElementById('enviarMensaje');

  // Añadimos un event listener al input para escuchar la tecla 'Enter'
  inputMensaje.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
      // Simulamos un clic en el botón cuando se presiona 'Enter'
      botonEnviar.click();
    }
  });