// Dependencias:
// - import.js

// const MYTUBE_IP = "http://192.168.1.137/DAM-B/TriConnect/my_tube";
const MYTUBE_IP = "http://10.3.5.111/DAM-B/TriConnect/my_tube";

// Iniciar sesión.
async function loginAPI(username, password) {
    let formData = new FormData();
    formData.append('USERNAME', username);
    formData.append('PASSWORD', password);

    let fetchData = await fetch(`${MYTUBE_IP}/php/login.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.text();
    return data;
}

// Registrarse.
async function registerAPI(username, password, email) {
    let formData = new FormData();
    formData.append('USERNAME', username);
    formData.append('PASSWORD', password);
    formData.append('EMAIL', email);

    let fetchData = await fetch(`${MYTUBE_IP}/php/sign_up.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.text();
    return data;
}

// Enviar mensaje.
async function sendMessageAPI(sender, receiver, msg, fromChatterly) {
    let formData = new FormData();
    formData.append('SENDER', sender);
    formData.append('RECEIVER', receiver);
    formData.append('MSG', msg);
    formData.append('FROM_CHATTERLY', fromChatterly);

    let fetchData = await fetch(`${MYTUBE_IP}/php/send_message.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.text();
    return data;
}

// Enviar imagen.
async function sendImageAPI(sender, receiver, image, fromChatterly) {
    let formData = new FormData();
    formData.append('SENDER', sender);
    formData.append('RECEIVER', receiver);
    formData.append('IMAGE', image);
    formData.append('FROM_CHATTERLY', fromChatterly);

    let fetchData = await fetch(`${MYTUBE_IP}/php/send_image.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.text();
    return data;
}

// Recibir mensajes.
async function receiveMessagesAPI(sender, receiver) {
    let formData = new FormData();
    formData.append('SENDER', sender);
    formData.append('RECEIVER', receiver);

    let fetchData = await fetch(`${MYTUBE_IP}/php/receive_messages.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.json();
    return data;
}

async function getFriendsAPI(username) {
    let formData = new FormData();
    formData.append('USERNAME', username);

    let fetchData = await fetch(`${MYTUBE_IP}/php/get_friends.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.json();
    return data;
}

// Parte visual del inicio de sesión y registro.
function getMain() {
    return document.getElementById("main_div");
}

function getLoginAPIWrapper() {
    return document.getElementById("mytube_login_API_wrapper");
}

function getLoginForm() {
    return document.getElementById("login_form");
}

function getRegister() {
    return document.getElementById("register_form");
}

// Mostrar la ventana de inicio de sesión (API).
function displayLoginAPIWrapper() {
    try {
        getLoginAPIWrapper().style.display = "";
        showLoginForm();
        getMain().style.filter = "brightness(20%)";
    } catch (e) { }
}

// Cerrar la ventana de inicio de sesión (API).
function closeLoginAPIWrapper() {
    try {
        getLoginAPIWrapper().style.display = "none";
        getMain().style.filter = "brightness(100%)";
    } catch (e) { }
}

// Mostrar el div de inicio de sesión.
function showLoginForm() {
    hideRegisterForm();
    getLoginForm().style.display = "";
}

// Ocultar el div de inicio de sesión.
function hideLoginForm() {
    getLoginForm().style.display = "none";
}

// Mostrar la ventana de registro.
function showRegisterForm() {
    hideLoginForm();
    getRegister().style.display = "";
}

// Cerrar la ventana de registro.
function hideRegisterForm() {
    getRegister().style.display = "none";
}

// Validar el form de inicio de sesión.
async function validateLoginForm() {
    event.preventDefault();
    let formData = new FormData(document.getElementById("login_form"))
    let data = await loginAPI(formData.get("USERNAME"), formData.get("PASSWORD"));
    if (data == "SUCCESS") {
        await fetch(`../php/set_session.php?USERNAME=${formData.get("USERNAME")}&PASSWORD=${formData.get("PASSWORD")}`, {
            method: "GET",
        });
    }

    checkErrors(data);
}

// Validar el form de inicio de sesión en Chatterly.
async function validateLoginFormChatterly() {
    event.preventDefault();
    let formData = new FormData(document.getElementById("login_form"))
    let data = await loginAPI(formData.get("USERNAME"), formData.get("PASSWORD"));
    // closeLoginAPIWrapper();

    return { "status": data, "user": formData.get("USERNAME") };
}

// Validar el form de registro.
async function validateRegisterForm() {
    event.preventDefault();
    let formData = new FormData(document.getElementById("register_form"))
    let data = await registerAPI(formData.get("USERNAME"), formData.get("PASSWORD"));
    checkErrors(data);
}

// Validar el form de registro en Chatterly.
async function validateRegisterFormChatterly() {
    event.preventDefault();
    let formData = new FormData(document.getElementById("register_form"))
    let data = await registerAPI(formData.get("USERNAME"), formData.get("PASSWORD"));
    checkErrors(data);
}

// Comprobar si existen errores en el inicio de sesión.
function checkErrors(data) {
    if (data == "SUCCESS") {
        location.reload();
    } else {
        createNotification(data);
    }
}

function createNotification(message) {
    alert(message);
}

// :TODO:
//// Crear notificaciones.
//let notifications = document.getElementById("notifications");
//async function createNotification(message) {
//    if (!notifications.innerHTML.includes(message)) {
//        notifications.innerHTML += "<div class='notification'>" + message + "</div>";
//    }
//}
//
//function getChannel(input, event) {
//    if (event.key == "Enter" && input.value != "") {
//        location.href = `../php/channel.php?channel_id=${input.value}`;
//    }
//}
//
//
//// Eliminar la última notificación cada segundo.
//setInterval(function () {
//    if (notifications != null && notifications.lastElementChild) {
//        notifications.lastElementChild.remove();
//    }
//}, 1000);
//