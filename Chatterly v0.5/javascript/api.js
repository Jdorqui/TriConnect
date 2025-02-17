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

async function enviarMensajes_Api(usuario, destinatario, mensaje) 
{
    let fetchData = await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/chat.php",
        {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `usuario=${encodeURIComponent(usuario)}&destinatario=${encodeURIComponent(destinatario)}&mensaje=${encodeURIComponent(mensaje)}`
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