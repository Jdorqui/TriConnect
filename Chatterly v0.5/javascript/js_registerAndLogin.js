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
    const formData = new FormData(document.getElementById("form-registro"));  //obtiene los datos del formulario

    fetch("../php/registro.php", {
        method: "POST",  //usa el método POST
        body: formData,
    })
    .then((response) => response.json())  //espera una respuesta JSON desde PHP
    .then((data) => {
        const errorMessage = document.getElementById("error-message-registro");
        errorMessage.style.display = "none";  //reseteamos el mensaje de error

        if (data.status === "success") 
        {
            mostrarLogin();  //vuelve al login
        } 
        else 
        {
            //muestra el mensaje de error si hubo un problema
            errorMessage.style.display = "block";
            errorMessage.style.color = "#f7767a";  //mostramos el mensaje de error
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
                window.location.href = "../php/chatterly.php";
            } 
            else 
            {
                errorMessage.textContent = data.message;

                errorMessage.style.color = "#f7767a";
                document.getElementById("ms3").style.color = "#f7767a";
                document.getElementById("ms4").style.color = "#f7767a";
                
                if(document.getElementById("ms3").textContent.includes(data.message) == false)
                    {
                        document.getElementById("ms3").textContent = document.getElementById("ms3").textContent + ` - ${data.message}`;
                        document.getElementById("ms4").textContent = document.getElementById("ms4").textContent + ` - ${data.message}`;
                    }
                
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
            mostrarLogin();
        })
        .catch((error) => console.error("Error:", error));
}

document.addEventListener("DOMContentLoaded", mostrarLogin); //muestra el login inicialmente