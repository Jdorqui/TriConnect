// Iniciar sesión
async function login(username, password) {
    let formData = new FormData();
    formData.append('USERNAME', username);
    formData.append('PASSWORD', password);

    let fetchData = await fetch("http://10.3.5.111/DAM-B/TriConnect/my_tube/php/login.php", {
        method: "POST",
        body: formData,
    })

    let data = await fetchData.text();
    return data;
}

async function register(username, password, email) {
    let formData = new FormData();
    formData.append('USERNAME', username);
    formData.append('PASSWORD', password);
    formData.append('EMAIL', email);

    let fetchData = await fetch("http://10.3.5.111/DAM-B/TriConnect/my_tube/php/sign_up.php", {
        method: "POST",
        body: formData,
    })

    let data = await fetchData.text();
    return data;
}

async function sendMessage(sender, receiver, msg) {
    let formData = new FormData();
    formData.append('SENDER', sender);
    formData.append('RECEIVER', receiver);
    formData.append('MSG', msg);

    let fetchData = await fetch("http://10.3.5.111/DAM-B/TriConnect/my_tube/php/send_message.php", {
        method: "POST",
        body: formData,
    })

    let data = await fetchData.text();
    return data;
}