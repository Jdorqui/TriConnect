async function getChatterlyUsername(username) {
    let formData = new FormData();
    formData.append('USERNAME', username);

    let fetchData = await fetch(`../php/get_chatterly_username.php?USERNAME=${username}`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.json();
    return data.CHATTERLY_USERNAME;
}

async function getChatterlyLogin() {
    await $('head').append('<link rel="stylesheet" type="text/css" href="http://10.3.5.106/PHP/TriConnect/css/style_login.css">');
    await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/html/index.html", {
        method: 'POST',
        mode: 'cors'
    })
        .then(response => response.text())
        .then(data => {
            
        });
}

(async () => {
    getChatterlyLogin();
})();
