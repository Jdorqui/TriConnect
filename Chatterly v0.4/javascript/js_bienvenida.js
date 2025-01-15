const normalPanel = document.getElementById("bienvenida");
const optionsPanel = document.getElementById("options");
const initialpanel = document.getElementById("initialpanel");
const chat = document.getElementById("chat");

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

function closeoinitialpanel()
{
    chat.style.display = "block";
    initialpanel.style.display = "none";
}

function closeoinitialpanel()
{
    initialpanel.style.display = "block";
    chat.style.display = "none";
}

function openaddfriendmenu()
{
    document.getElementById("addfriendmenu").style.display = "block";
}

function closeaddfriendmenu()
{
    document.getElementById("addfriendmenu").style.display = "none";
}

function openpendingmenu() {
        const pendingMenu = document.getElementById('pendingmenu');
        const friendTabButton = document.querySelector('.friend-tab-button:nth-child(3)');
        
        // Si el menú está oculto, lo mostramos, si no lo ocultamos
        if (pendingMenu.hidden) {
            pendingMenu.hidden = false;
            friendTabButton.innerHTML = "Cerrar Pendientes";
        } else {
            pendingMenu.hidden = true;
            friendTabButton.innerHTML = "Pendiente";
        }
    }

function fetchPendingRequests() {
    fetch('../php/get_pending_requests.php')
        .then(response => response.json())
        .then(data => {
            const pendingMenu = document.getElementById('pendingmenu');
            if (data.length > 0) {
                pendingMenu.innerHTML = ''; // Limpiar contenido previo
                data.forEach(request => {
                    const div = document.createElement('div');
                    div.innerHTML = `
                        <p>${request.alias}</p>
                        <button onclick="manageRequest(${request.id}, 'aceptar')">Aceptar</button>
                        <button onclick="manageRequest(${request.id}, 'rechazar')">Rechazar</button>
                    `;
                    pendingMenu.appendChild(div);
                });
            } else {
                pendingMenu.innerHTML = '<p>No tienes solicitudes pendientes.</p>';
            }
        })
        .catch(error => console.error('Error al obtener solicitudes pendientes:', error));
}

function manageRequest(id, action) {
    fetch('../php/gestionar_solicitud.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `solicitante=${id}&accion=${action}`
    })
        .then(response => response.text())
        .then(message => {
            alert(message);
            fetchPendingRequests(); // Refrescar las solicitudes pendientes
        })
        .catch(error => console.error('Error al gestionar la solicitud:', error));
}


function actualizarResultado(mensaje) 
{
    document.getElementById('resultado').innerText = mensaje;
}