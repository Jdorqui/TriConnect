async function mytubeconexion()
{
    await fetch('http://10.3.5.111/DAM-B/TriConnect/my_tube/php/api.php', {
        method: 'POST',
        mode: 'cors'
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        document.getElementById('mytubeconexion').innerHTML = data;
        document.getElementById('mytubeconexion').style.display = 'block';
    })
    .catch(error => console.error(error));

    // Obtener las imágenes desde el servidor remoto
    await fetch('http://10.3.5.111/DAM-B/TriConnect/my_tube/img/mytube_logo.png', {
        method: 'GET',
        mode: 'cors'
    })
    .then(response => response.blob())  // Obtener la imagen como un blob
    .then(blob => {
        // Crear una URL para la imagen y asignarla al src del <img>
        const imageUrl = URL.createObjectURL(blob);
        document.getElementById('mytube_logo').src = imageUrl;
    })
    .catch(error => console.error('Error al cargar la imagen mytube_logo:', error));

    await fetch('http://10.3.5.111/DAM-B/TriConnect/my_tube/img/x_button.png', {
        method: 'GET',
        mode: 'cors'
    })
    .then(response => response.blob())  // Obtener la imagen como un blob
    .then(blob => {
        // Crear una URL para la imagen y asignarla al src del <img>
        const imageUrl = URL.createObjectURL(blob);
        document.getElementById('x_button').src = imageUrl;
    })
    .catch(error => console.error('Error al cargar la imagen x_button:', error));

    document.getElementById("login_form").addEventListener('submit', async function(event) 
    {
        var datos = await validateLoginFormChatterly(event);

        if (datos.status == 'SUCCESS')
        {
            $.get(`../php/insertarUsuarioMytube_Api.php?mytube=${datos.user}&id_user=${id_usuario_actual}`);
        }
    });
}

async function login_mytube(usuario, password, email)
{
    console.log(await login(usuario, password));

    if (await login(usuario, password) == "SUCCESS")
    {
        console.log("entra");
    }
    else
    {
        console.log(await register(usuario, password, email));
    }
}