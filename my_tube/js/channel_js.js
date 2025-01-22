function subscribe(username, channelID) {
    let subscribe_button = document.getElementById("subscription");

    fetch(`../php/subscribe.php?username=${username}&channel_id=${channelID}`, {
        method: "GET"
    })
        .then((response) => response.text())
        .then((data) => {
            console.log(data);
            if (data.match('0')) {
                subscribe_button.parentElement.innerHTML = '<div class="subscribe_button" onclick="unsubscribe(username, channelID)" id="subscription">Suscrito</div>';
            } else if (data.match('1')) {
                subscribe_button.parentElement.innerHTML = `<div class="subscribe_button" onclick="unsubscribe(username, channelID)" id="subscription" style="background-color: blue">Amigos</div><div id="chat_button" onclick="location.href='chat.php'">Enviar mensaje</div>`;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function unsubscribe(username, channelID) {
    let unsubscribe_button = document.getElementById("subscription");

    fetch(`../php/unsubscribe.php?username=${username}&channel_id=${channelID}`, {
        method: "GET"
    })
        .then((response) => response.text())
        .then((data) => {
            console.log(data);
            unsubscribe_button.parentElement.innerHTML = '<div class="subscribe_button" onclick="subscribe(username, channelID)" id="subscription">Suscribirse</div>';
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}