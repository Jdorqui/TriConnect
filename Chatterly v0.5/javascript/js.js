const loginDiv = document.getElementById("loginDiv");
const registroDiv = document.getElementById("registroDiv");
const bgBody = document.body;

function mostrarLogin() 
{
    const loginDiv = document.getElementById("loginDiv");
    const registroDiv = document.getElementById("registroDiv");

    registroDiv.classList.remove("show");
    setTimeout(() => {
        registroDiv.style.display = "none";
    }, 500);

    loginDiv.style.display = "block";
    setTimeout(() => {
        loginDiv.classList.add("show");
    }, 10);
}

function mostrarRegistro() 
{
    const loginDiv = document.getElementById("loginDiv");
    const registroDiv = document.getElementById("registroDiv");

    loginDiv.classList.remove("show");
    setTimeout(() => {
        loginDiv.style.display = "none";
    }, 500);

    registroDiv.style.display = "block";
    setTimeout(() => {
        registroDiv.classList.add("show");
    }, 10); 
}

function registrarUsuario() 
{
    const formData = new FormData(document.getElementById("form-registro"));  // obtiene los datos del formulario

    fetch("../php/registro.php", {
        method: "POST",  // usa el método POST
        body: formData,
    })
    .then((response) => response.json())  // espera una respuesta JSON desde PHP
    .then((data) => {
        const errorMessage = document.getElementById("error-message-registro");
        errorMessage.style.display = "none";  // Reseteamos el mensaje de error

        if (data.status === "success") 
        {
            alert(data.message);  // muestra el mensaje de éxito
            mostrarLogin();  // vuelve al login
        } 
        else 
        {
            // Muestra el mensaje de error si hubo un problema
            errorMessage.style.display = "block";  // Mostramos el mensaje de error
            errorMessage.textContent = data.message;
        }
    })
    .catch((error) => {
        console.error("Error:", error);
        const errorMessage = document.getElementById("error-message-registro");
        errorMessage.style.display = "block";
        errorMessage.textContent = "Hubo un error al procesar la solicitud. Intenta nuevamente más tarde.";
    });
}

function loginUsuario() 
{
    const usuario = document.getElementById("usuario-login").value;
    const password = document.getElementById("password-login").value;
    const errorMessage = document.getElementById("error-message");

    errorMessage.style.display = "none";
    errorMessage.textContent = "";

    fetch("../php/login.php", 
    {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") 
            {
                window.location.href = "../php/bienvenida.php";
            } 
            else 
            {
                errorMessage.textContent = data.message;

                errorMessage.style.color = "#f7767a";
                document.getElementById("ms3").style.color = "#f7767a";
                document.getElementById("ms4").style.color = "#f7767a";

                document.getElementById("ms3").textContent += ` - ${data.message}`;
                document.getElementById("ms4").textContent += ` - ${data.message}`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
}

function cerrarSesion()
{
    fetch("../php/logout.php", { method: "POST" })
        .then(() => {
            alert("Sesión cerrada.");
            mostrarLogin();
        })
        .catch((error) => console.error("Error:", error));
}

function enviarCorreo() {
    const email = document.getElementById('email').value.trim();
    const mensaje = document.getElementById('mensaje');

    // Validación básica del correo
    if (!email || !/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/.test(email)) {
        mensaje.textContent = 'Por favor, ingresa un correo válido.';
        mensaje.style.color = 'red';
        return;
    }

    // Enviar la solicitud al backend para enviar el correo
    fetch('../php/forgotPassword.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `email=${encodeURIComponent(email)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mensaje.textContent = 'Te hemos enviado un correo para recuperar tu contraseña.';
            mensaje.style.color = 'green';
        } else {
            mensaje.textContent = data.error;
            mensaje.style.color = 'red';
        }
    })
    .catch(error => {
        mensaje.textContent = 'Hubo un problema al enviar el correo. Intenta de nuevo más tarde.';
        mensaje.style.color = 'red';
    });
}

document.addEventListener("DOMContentLoaded", mostrarLogin); //muestra el login inicialmente