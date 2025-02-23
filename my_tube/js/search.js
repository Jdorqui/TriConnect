// Dependencias:
// - main.js

search_input.addEventListener("keyup", (event) => {
    search(search_input.value, event);
});

function search(searchQuery, event) {
    // /Key[A-Z0-9]/.test(event.code)

    if (divDisplaying == 'search' || event.code == 'Enter' || event == 'test') {
        display('search');

        let formData = new FormData();
        formData.append('username', username);
        formData.append('search_query', searchQuery);

        fetch('../php/receive_channels_and_videos.php', {
            method: "POST",
            body: formData
        })
            .then((response) => response.text())
            .then((data) => {
                let array = JSON.parse(data);
                let usernames = array["usernames"]
                let subscribed = array["subscribed"]
                let friends = array["friends"]

                channels_main_div.innerHTML = '';
                for (let i = 0; i < usernames.length; i++) {
                    createChannelDiv(usernames[i].toLowerCase(), subscribed, friends);
                }
            });
    }
}

function createChannelDiv(channel, subscribed, friends) {
    let type;
    if (friends.includes(channel)) {
        type = `<div onclick="unsubscribe(this, '${username}', '${channel}')" class="unsubscription friend_button">Amigos</div><div class="chat_button" onclick="display('chat')">Enviar mensaje</div>`;
    } else if (subscribed.includes(channel)) {
        type = `<div onclick="unsubscribe(this, '${username}', '${channel}')" class="unsubscription">Suscrito</div>`;
    } else {
        type = `<div onclick="subscribe(this, '${username}', '${channel}')" class="subscription">Suscribirse</div>`;
    }

    channels_main_div.innerHTML += `
        <div onclick="changeChannel()">
            <img class="search_user_image" src="../img/logged_out_profile_pic.jpg">
            <div>
                <div>${channel}</div>
                <div>subs</div>
            </div>
            <div style="z-index: 1">
                ${type}
            </div>
        </div>`;
}