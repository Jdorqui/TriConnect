// Dependencias:
// - api.js
// - api.js (Chatterly)
// - chatterly.js

const FRIENDS_NAVBAR = document.getElementById('friend_navbar');
const USER_HEADER = document.getElementById('user_header');
const CHAT = document.getElementById('chat');
const FRIEND_OBJECTS = [];
let friendsArray = [];

let totalUnreadMessages = 0;
let totalUnreadMessagesDiv = document.getElementById('new_messages_chat_tab');
function zeroTotalUnreadMessages() {
    totalUnreadMessages = 0;
    setUnreadMessagesDiv(totalUnreadMessagesDiv, totalUnreadMessages);
}

function setUnreadMessagesDiv(div, unreadMessages) {
    if (unreadMessages > 99) {
        div.innerHTML = '+99';
        div.style.display = '';
    } else if (unreadMessages > 0) {
        div.innerHTML = unreadMessages;
        div.style.display = '';
    } else {
        div.innerHTML = 0;
        div.style.display = 'none';
    }
}

let selectedFriend;
class FriendHandler {
    constructor(div, id, name) {
        this.div = div;
        this.id = id;
        this.name = name;
        this.messageNumber = 0;
        this.unreadMessages = 0;
    }

    setMesssageNumberToZero() {
        this.messageNumber = 0;
        CHAT.innerHTML = "";
    }

    checkUnreadMessages() {
        let UMDiv = document.getElementById(`friend_${this.id}`).lastElementChild;
        setUnreadMessagesDiv(UMDiv, this.unreadMessages);
        setUnreadMessagesDiv(totalUnreadMessagesDiv, totalUnreadMessages);
    }

    addUnreadMessage() {
        this.unreadMessages += 1;
        totalUnreadMessages += 1;
        this.checkUnreadMessages();
    }

    subtractUnreadMessage() {
        this.unreadMessages -= 1;
        totalUnreadMessages -= 1;
        this.checkUnreadMessages();
    }

    zeroUnreadMessages() {
        totalUnreadMessages -= this.unreadMessages;
        if (totalUnreadMessages == 0) {
            zeroTotalUnreadMessages();
        }

        this.unreadMessages = 0;
        this.checkUnreadMessages();
    }
}

async function createFriendDivs() {
    zeroTotalUnreadMessages();
    for (let i = 0; i < friendsArray.length; i++) {
        let name = friendsArray[i].SUBSCRIBED_TO;

        FRIENDS_NAVBAR.innerHTML += `
            <div id=friend_${i} onclick="changeChat(${i})" style="position: relative">
                <img class="every_user_image" src="../../../../../uploads/${name}/profile_pic.png">
                <div>${name}</div>
                <div class="new_messages_each_friend" style="display: none">0</div>
            </div>`;

        FRIEND_OBJECTS.push(new FriendHandler(FRIENDS_NAVBAR.children[i + 1], i, name));
    }

    emptyChat();
    if (FRIEND_OBJECTS.length > 0) {
        document.getElementById("input_text").style.display = "";
    } else {
        emptyChat();
        document.getElementById("input_text").style.display = "none";
    }
}

function changeChat(friendId) {
    selectedFriend = FRIEND_OBJECTS[friendId];
    selectedFriend.setMesssageNumberToZero();

    USER_HEADER.innerHTML = `
        <img class="every_user_image" src="../../../../../uploads/${selectedFriend.name}/profile_pic.png">
        <div>${selectedFriend.name}</div>`;

    setReadMessages();
}

function emptyChat() {
    USER_HEADER.innerHTML = "";
    CHAT.innerHTML = "";
}

(async () => {
    friendsArray = await getFriendsAPI(username);
    await createFriendDivs();
})();

function setReadMessages() {
    let formData = new FormData();
    formData.append('sender', username);
    formData.append('receiver', selectedFriend.name);

    fetch('../php/read_message.php', { method: "POST", body: formData });
    selectedFriend.zeroUnreadMessages();
}

function createMessage(jsonData, date) {
    let style = "margin-right: auto"
    let colorStyle = "background-color: rgba(65, 65, 65, 0.27);";
    if (jsonData.SENDER == username) {
        style = "margin-left: auto"
        colorStyle = "background-color: rgba(255, 0, 0, 0.267);"
    }

    let senderImg = `../../../../../uploads/${jsonData.SENDER}/profile_pic.png`;
    let before = CHAT.innerHTML;
    let content = jsonData.MSG;
    if (jsonData.IS_FILE == 1) {
        content = `<img src="../../../../../uploads/${jsonData.SENDER}/${jsonData.MSG}">`;
    }

    if (jsonData.FROM_CHATTERLY == 1) {
        CHAT.innerHTML = `
        <div class="message_body" style="${style}">
            <img class="every_user_image" src="../img/chatterly_logo.png">
            <div style="${colorStyle}; background-color: #6458aa">
                <div style="font-size: 1vw;">${jsonData.SENDER}</div>
                <div class="message">${content}</div>
                <div style="position: absolute; right: 3%; bottom: 10%; font-size: 1vw;">${date}</div>
            </div>
        </div>`;
    } else {
        CHAT.innerHTML = `
        <div class="message_body" style="${style}">
            <img class="every_user_image" src="${senderImg}">
            <div style="${colorStyle}">
                <div style="font-size: 1vw;">${jsonData.SENDER}</div>
                <div class="message">${content}</div>
                <div style="position: absolute; right: 3%; bottom: 10%; font-size: 1vw;">${date}</div>
            </div>
        </div>`;
    }

    CHAT.innerHTML += before;
}

// Función que maneja el envío de los mensajes a las diferentes bases de datos.
async function sendMessage(input, event) {
    if (event.key == "Enter" && input.value != "") {
        let inputValue = input.value;
        input.value = "";

        // Enviar mensaje a la base de datos de MyTube.
        await sendMessageAPI(username, selectedFriend.name, inputValue);

        // Verificar si Chatterly está disponible.
        if (typeof usuarioNumero_Api === "function") {
            // Transformar datos de MyTube a datos de Chatterly.
            let chatterlyUsername = await getChatterlyUsername(username);
            let chatterlyFriend = await getChatterlyUsername(selectedFriend.name);

            // Usar API de Chatterly.
            let chatterlyUsernameID = await usuarioNumero_Api(chatterlyUsername);
            let chatterlyFriendID = await usuarioNumero_Api(chatterlyFriend);

            // Verificar si son amigos en Chatterly.
            if (await esamigos_Api(chatterlyUsernameID, chatterlyFriendID) == 'aceptado') {
                // Enviar mensajes a la base de datos de Chatterly.
                await enviarMensajes_Api(chatterlyUsernameID, chatterlyFriendID, inputValue, 1);
            }
        }

        // Cuando el usuario envía mensaje, la vista del chat se coloca al final.
        CHAT.scrollTop = CHAT.scrollHeight
    }
}

// Usar la función receiveMessages de la API para recibir todos los mensajes y crearlos.
// Esta función solo crea los nuevos mensajes.
async function getAllMessages(friendObject) {
    let jsonData = await receiveMessagesAPI(username, friendObject.name);
    let newMessages = jsonData.length - friendObject.messageNumber;

    for (let i = 0; i < newMessages; i++) {
        let sender = jsonData[friendObject.messageNumber].SENDER;
        let seen = jsonData[friendObject.messageNumber].SEEN;

        // Comprobar si el mensaje nuevo recibido es del amigo seleccionado.
        if (selectedFriend != null && friendObject.name == selectedFriend.name) {
            let rawDate = new Date(jsonData[friendObject.messageNumber].SEND_DATE);
            let date = rawDate.toLocaleString();
            if (rawDate.toLocaleDateString() == new Date().toLocaleDateString()) {
                date = rawDate.toLocaleTimeString();
            }

            createMessage(jsonData[friendObject.messageNumber], date);
        }
        // Por otro lado, añade 1 a los mensajes no vistos por el usuario actual.
        else if (seen == "0" && sender != username) {
            friendObject.addUnreadMessage();
        }

        friendObject.messageNumber += 1;
    }
}

// Comprobar cada 250 ms todos los mensajes de cada amigo y comprueba la lista de amigos.
setInterval(async function () {
    let tempArray = await getFriendsAPI(username);
    if (friendsArray.length != tempArray.length) {
        FRIEND_OBJECTS.length = 0;
        FRIENDS_NAVBAR.innerHTML = "<div>Amigos</div>"
        friendsArray = tempArray;
        await createFriendDivs();
    }

    for (i = 0; i < FRIEND_OBJECTS.length; i++) {
        await getAllMessages(FRIEND_OBJECTS[i]);
    }
}, 250);