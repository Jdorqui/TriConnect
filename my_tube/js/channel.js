// Dependencias:
// - api.js

function changeChannel() {
    // display('channel');
}

function subscribe(button, username, channel) {
    if (username == '') {
        displayLoginAPIWrapper();
        return;
    }

    fetch(`../php/subscribe.php?username=${username}&channel=${channel}`, {
        method: "GET"
    })
        .then((response) => response.text())
        .then((data) => {
            // console.log(data);
            if (data.match('0')) {
                button.parentElement.innerHTML = `<div onclick="unsubscribe(this, '${username}', '${channel}')" class="unsubscription">Suscrito</div>`;
            } else if (data.match('1')) {
                button.parentElement.innerHTML = `<div onclick="unsubscribe(this, '${username}', '${channel}')" class="unsubscription friend_button">Amigos</div><div class="chat_button" onclick="location.href='chat.php'">Enviar mensaje</div>`;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function unsubscribe(button, username, channel) {
    fetch(`../php/unsubscribe.php?username=${username}&channel=${channel}`, {
        method: "GET"
    })
        .then((response) => response.text())
        .then((data) => {
            // console.log(data);
            button.parentElement.innerHTML = `<div onclick="subscribe(this, '${username}', '${channel}')" class="subscription">Suscribirse</div>`;
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}