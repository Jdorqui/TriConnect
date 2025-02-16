const MYTUBE_IP = "172.25.170.9";
// const MYTUBE_IP = "192.168.1.137";

// Iniciar sesión.
async function login(username, password) {
    let formData = new FormData();
    formData.append('USERNAME', username);
    formData.append('PASSWORD', password);

    let fetchData = await fetch(`http://${MYTUBE_IP}/DAM-B/TriConnect/my_tube/php/login.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.text();
    return data;
}

// Registrarse.
async function register(username, password, email) {
    let formData = new FormData();
    formData.append('USERNAME', username);
    formData.append('PASSWORD', password);
    formData.append('EMAIL', email);

    let fetchData = await fetch(`http://${MYTUBE_IP}/DAM-B/TriConnect/my_tube/php/sign_up.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.text();
    return data;
}

// Enviar mensaje.
async function sendMessageAPI(sender, receiver, msg) {
    let formData = new FormData();
    formData.append('SENDER', sender);
    formData.append('RECEIVER', receiver);
    formData.append('MSG', msg);

    let fetchData = await fetch(`http://${MYTUBE_IP}/DAM-B/TriConnect/my_tube/php/send_message.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.text();
    return data;
}

// Recibir mensajes.
async function receiveMessages(sender, receiver) {
    let formData = new FormData();
    formData.append('SENDER', sender);
    formData.append('RECEIVER', receiver);

    let fetchData = await fetch(`http://${MYTUBE_IP}/DAM-B/TriConnect/my_tube/php/receive_messages.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.json();
    return data;
}

async function getFriends(username) {
    let formData = new FormData();
    formData.append('USERNAME', username);

    let fetchData = await fetch(`http://${MYTUBE_IP}/DAM-B/TriConnect/my_tube/php/get_friends.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.json();
    return data;
}

// Parte visual del inicio de sesión y registro.
let main = document.getElementById("main_div");
let loginAPIWrapper = document.getElementById("mytube_login_API_wrapper");
let loginForm = document.getElementById("login_form");
let registerForm = document.getElementById("register_form");

// Mostrar la ventana de inicio de sesión (API).
function displayLoginAPIWrapper() {
    loginAPIWrapper.style.display = "";
    showLoginForm();
    main.style.filter = "brightness(20%)";
}

// Cerrar la ventana de inicio de sesión (API).
function closeLoginAPIWrapper() {
    loginAPIWrapper.style.display = "none";
    main.style.filter = "brightness(100%)";
}

// Mostrar el div de inicio de sesión.
function showLoginForm() {
    hideRegisterForm();
    loginForm.style.display = "";
}

// Ocultar el div de inicio de sesión.
function hideLoginForm() {
    loginForm.style.display = "none";
}

// Mostrar la ventana de registro.
function showRegisterForm() {
    hideLoginForm();
    registerForm.style.display = "";
}

// Cerrar la ventana de registro.
function hideRegisterForm() {
    registerForm.style.display = "none";
}

// Validar el form de inicio de sesión.
async function validateLoginForm() {
    event.preventDefault();
    let formData = new FormData(document.getElementById("login_form"))
    let data = await login(formData.get("USERNAME"), formData.get("PASSWORD"));
    if (data == "SUCCESS") {
        await $.get(`../php/set_session.php?USERNAME=${formData.get("USERNAME")}&PASSWORD=${formData.get("PASSWORD")}`);
    }

    checkErrors(data);
}

// Validar el form de registro.
async function validateRegisterForm() {
    event.preventDefault();
    let formData = new FormData(document.getElementById("register_form"))
    let data = await register(formData.get("USERNAME"), formData.get("PASSWORD"));
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