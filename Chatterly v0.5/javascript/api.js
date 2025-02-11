async function login_Api(usuario, password) 
{
    let fetchData = await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/login.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}`
        });
    let data = await fetchData.json();
    if (data.status === "success") 
    {
        console.log("success");
        return data;
    }
    else
    {
        errorMessage.textContent = data.message;
        console.log("success'nt");
    }
}

function chat_api(usuario, destinatario, mensaje) 
{
    $.post('http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/chat.php', { usuario: usuario, mensaje: mensaje, destinatario: destinatario }, function () { // Enviar mensaje al servidor 
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
    return data;
}

async function esamigos_Api(usuario, destinatario) 
{
    let fetchData = await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/gestionar_solicitud.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `usuario=${encodeURIComponent(usuario)}&destinatario=${encodeURIComponent(destinatario)}`
        });
    let data = await fetchData.json();
    
}