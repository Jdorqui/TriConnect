let main = document.getElementById("main_div");
let loginAPIWrapper = document.getElementById("mytube_login_API_wrapper");
let loginForm = document.getElementById("login_form");
let registerForm = document.getElementById("register_form");

// Mostrar la ventana de inicio de sesión (API).
function displayLoginAPIWrapper() {
    loginAPIWrapper.style.display = "";
    showLoginForm()
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
function validateLoginForm() {
    console.log("bro");

    event.preventDefault();

    fetch("../php/login.php", {
        method: "POST",
        body: new FormData(document.getElementById("login_form")),
    })
        .then((response) => response.text())
        .then((data) => {
            console.log(data);
            checkLoginErrors(data);
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

// Validar el form de registro.
function validateRegisterForm() {
    event.preventDefault();

    fetch("../php/sign_up.php", {
        method: "POST",
        body: new FormData(document.getElementById("register_form")),
    })
        .then((response) => response.text())
        .then((data) => {
            checkRegisterErrors(data);
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

// Comprobar si existen errores en el inicio de sesión.
function checkLoginErrors(data) {
    if (data.includes("ERROR-000")) {
        createNotification("Conexión fallida con la base datos. Inténtelo más tarde.");
    } else if (data.includes("ERROR-001")) {
        createNotification("El usuario no existe.");
    } else if (data.includes("ERROR-002")) {
        createNotification("La contraseña no es correcta.");
    } else if (data.includes("SUCCESS")) {
        location.reload();
    } else {
        alert(data)
    }
}

// Comprobar si existen errores en el registro.
function checkRegisterErrors(data) {
    if (data.includes("ERROR-000")) {
        createNotification("Conexión fallida con la base datos. Inténtelo más tarde.");
    } else if (data.includes("ERROR-001")) {
        createNotification("El usuario ya existe.");
    } else if (data.includes("SUCCESS")) {
        location.reload();
    }
}

// Crear notificaciones.
let notifications = document.getElementById("notifications");
async function createNotification(message) {
    if (!notifications.innerHTML.includes(message)) {
        notifications.innerHTML += "<div class='notification'>" + message + "</div>";
    }
}

function getChannel(input, event) {
    if (event.key == "Enter" && input.value != "") {
        location.href = `../php/channel.php?channel_id=${input.value}`;
    }
}

// TODO
// Eliminar la última notificación cada segundo.
setInterval(function () {
    if (notifications != null && notifications.lastElementChild) {
        notifications.lastElementChild.remove();
    }
}, 1000);

// displayLoginAPIWrapper();