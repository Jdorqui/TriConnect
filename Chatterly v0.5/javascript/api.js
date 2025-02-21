function getloginDiv()
{
    return document.getElementById("loginDiv"); 
}

function getregistroDiv()
{
    return document.getElementById("registroDiv");
}

function cerrarLogin()
{
    getloginDiv().style.display = "none";
}

function mostrarLogin() //animacion
{
    getregistroDiv().classList.remove("show");
    setTimeout(() => {
        getregistroDiv().style.display = "none";
    }, 500);

    getloginDiv().style.display = "block";
    setTimeout(() => {
        getloginDiv().classList.add("show");
    }, 10);
}

function mostrarRegistro() //animacion
{
    getloginDiv().classList.remove("show");
    setTimeout(() => {
        getloginDiv().style.display = "none";
    }, 500);

    getregistroDiv().style.display = "block";
    setTimeout(() => {
        getregistroDiv().classList.add("show");
    }, 10); 
}

async function registrarUsuario_Api() //verifica errores del registro y registra los usuarios
{
    const formData = new FormData(document.getElementById("form-registro")); 

    let fetchData = await fetch(`http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/registro.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.json();

    try
    {
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
    }
    catch(e)
    {
        console.error("Error:", error);
        const errorMessage = document.getElementById("error-message-registro");
        errorMessage.style.display = "block";
        errorMessage.textContent = "Hubo un error al procesar la solicitud. Intenta nuevamente más tarde.";
    }
}

async function login_Api(usuario, password) //devuelve si login.php success
{
    let fetchData = await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/login.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}`
        });

    let data = await fetchData.json();
    return data;
}

async function loginUsuario_Api() //verifica errores del login
{
    const usuario = document.getElementById("usuario-login").value;
    const password = document.getElementById("password-login").value;
    const errorMessage = document.getElementById("error-message");

    errorMessage.style.display = "none";
    errorMessage.textContent = "";

    let data = await login_Api(usuario, password); //se usa el metodo login_api                                               

    if (data.status !== "success") 
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

    return data;
}

async function enviarMensajes_Api(usuario, destinatario, mensaje, mytube)
{
    //console.log(mytube);
    await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/chat.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `usuario=${encodeURIComponent(usuario)}&destinatario=${encodeURIComponent(destinatario)}&mensaje=${encodeURIComponent(mensaje)}&mytube=${encodeURIComponent(mytube)}`
        });
}

async function cargarMensajes_Api(usuario, destinatario) 
{
    let fetchData = await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/chat.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `usuario=${encodeURIComponent(usuario)}&destinatario=${encodeURIComponent(destinatario)}`
        });
    let data = await fetchData.json();
    //console.log(data);
    return data; //devuelve un array con los mensajes en formato json
}

async function esamigos_Api(usuario1, usuario2) 
{
    let fetchData = await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/verificarAmistad_Api.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `usuario1=${encodeURIComponent(usuario1)}&usuario2=${encodeURIComponent(usuario2)}`
        });
    let data = await fetchData.json();
    return data.estado;
}

async function usuarioNumero_Api(usuario) 
{
    let fetchData = await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/usuarioNumero_Api.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `usuario=${encodeURIComponent(usuario)}`
        });
    let data = await fetchData.json();
    return data.id_user;
}

async function numeroUsuario_Api(id_user) 
{
    let fetchData = await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/numeroUsuario_Api.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id_user=${encodeURIComponent(id_user)}`
        });
    let data = await fetchData.json();
    return data.mytube;
}