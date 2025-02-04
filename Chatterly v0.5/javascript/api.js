function mytubeconexion()
{
    fetch('http://10.3.5.111/DAM-B/TriConnect/my_tube/php/api.php', {
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

    fetch('http://10.3.5.111/DAM-B/TriConnect/my_tube/js/login_api.js', {
        method: 'POST',
        mode: 'cors'
    })
    .then(response => response.text())
    .then(data => {
        // Crear un nuevo <script> y añadir el código JavaScript al contenido
        const script = document.createElement('script');
        script.type = 'text/javascript';
        script.innerHTML = data;  // Añadir el código JavaScript recibido
    
        document.head.appendChild(script);
    })
    .catch(error => console.error(error));
    
    // Obtener las imágenes desde el servidor remoto
    fetch('http://10.3.5.111/DAM-B/TriConnect/my_tube/img/mytube_logo.png', {
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

    fetch('http://10.3.5.111/DAM-B/TriConnect/my_tube/img/x_button.png', {
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
}