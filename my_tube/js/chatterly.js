// Dependencias:
// - import.js
// - js_registerAndLogin.js (Chatterly)
// - api.js (Chatterly)

async function getChatterlyUsername(username) {
    let formData = new FormData();
    formData.append('USERNAME', username);

    let fetchData = await fetch(`../php/get_chatterly_username.php`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.json();
    return data.CHATTERLY_USERNAME;
}

async function getChatterlyLogin() {
    let fetchData = await fetch(`${CHATTERLY_IP}/html/index_Api.html`, {
        method: 'POST',
        mode: 'cors'
    });

    document.getElementById('chatterly_login').innerHTML = fetchData;
    document.getElementById('btn-submit-login').addEventListener('click', async () => {
        let data = await loginUsuario_Api();
        if (data.status === 'success') {
            let chatterlyUsername = document.getElementById('usuario-login').value;
            await $.get(`../php/set_chatterly_username.php?USERNAME=${username}&CHATTERLY_USERNAME=${chatterlyUsername}`);
        }
    });
}