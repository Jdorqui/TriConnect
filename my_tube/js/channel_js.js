function subscribe(username, channelID) {
    let subscribe_button = document.getElementById("subscription");

    fetch(`../php/subscribe.php?username=${username}&channel_id=${channelID}`, {
        method: "GET"
    })
        .then((response) => response.text())
        .then((data) => {
            console.log(data);
            subscribe_button.parentElement.innerHTML = '<div class="subscribe_button" onclick="unsubscribe(username, channelID)" id="subscription">Suscrito</div><div id="chat_button">Enviar mensaje</div>';
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