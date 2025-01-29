function mytubeconexion()
{
    fetch('http://10.3.5.111/DAM-B/TriConnect/my_tube/php/api.php', {
        method: 'POST',
        mode: 'cors'
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
    })
    .catch(error => console.error(error));
}