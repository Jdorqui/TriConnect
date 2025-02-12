const friendsNavbar = document.getElementById('friend_navbar');
const chat = document.getElementById('chat');

const friendObjects = [];
let selectedFriend;
class FriendHandler {
    constructor(div, id, name) {
        this.div = div;
        this.id = id;
        this.name = name;
        this.messsageNumber = 0;
        this.unreadMessages = 0;
    }

    setMesssageNumberToZero() {
        this.messageNumber = 0;
        chat.innerHTML = "";
    }

    checkUnreadMessages() {
        let UMDiv = document.getElementById(`friend_${this.id}`).lastElementChild;

        if (this.unreadMessages > 99) {
            UMDiv.innerHTML = '+99';
            UMDiv.style.display = '';
        } else if (this.unreadMessages > 0) {
            UMDiv.innerHTML = this.unreadMessages;
            UMDiv.style.display = '';
        } else {
            UMDiv.innerHTML = 0;
            UMDiv.style.display = 'none';
        }
    }

    addUnreadMessage() {
        this.unreadMessages += 1;
        this.checkUnreadMessages();
    }

    subtractUnreadMessage() {
        this.unreadMessages -= 1;
        this.checkUnreadMessages();
    }

    zeroUnreadMessages() {
        this.unreadMessages = 0;
        this.checkUnreadMessages();
    }
}

function createFriendDivs() {
    for (let i = 0; i < friendsArray.length; i++) {
        let name = friendsArray[i][0];

        friendsNavbar.innerHTML += `
            <div id=friend_${i} onclick="changeChat(${i})" style="position: relative">
                <img class="every_user_image" src="../img/profile_pic_example.jpg">
                <div>${name}</div>
                <div class="new_messages_each_friend" style="display: none">0</div>
            </div>`;

        friendObjects.push(new FriendHandler(friendsNavbar.children[i + 1], i, name));
    }

    // console.log(friendObjects);
}

function changeChat(friendId) {
    selectedFriend = friendObjects[friendId];
    selectedFriend.setMesssageNumberToZero();

    let userHeader = document.getElementById('user_header');
    userHeader.innerHTML = `
        <img class="every_user_image" src="../img/profile_pic_example.jpg">
        <div>${selectedFriend.name}</div>`;

    setReadMessages();
}

function setReadMessages() {
    let formData = new FormData();
    formData.append('sender', username);
    formData.append('receiver', selectedFriend.name);

    fetch('../php/read_message.php', { method: "POST", body: formData });
    selectedFriend.zeroUnreadMessages();
}

createFriendDivs();
if (friendObjects.length > 0) {
    changeChat(friendObjects[0].id);
}

function createMessage(sender, msg) {
    let style = "";
    let colorStyle = "";
    if (sender == username) {
        style = "margin-left: auto"
        colorStyle = "background-color: rgba(255, 0, 0, 0.267);"
    } else {
        style = "margin-right: auto"
        colorStyle = "background-color: rgba(65, 65, 65, 0.27);";
    }

    let before = chat.innerHTML;

    chat.innerHTML = `
        <div class="message_body" style="${style}">
            <img class="every_user_image" src="../img/profile_pic_example.jpg">
            <div style="${colorStyle}">
                <div>${sender}</div>
                <div class="message">${msg}</div>
            </div>
        </div>`;

    chat.innerHTML += before;
}

// Usar la función receiveMessages de la API para mandar un mensaje.
async function sendMessage(input, event) {
    if (event.key == "Enter" && input.value != "") {
        await sendMessageAPI(username, selectedFriend.name, input.value);

        let chatterlyUsername = await getChatterlyUsername(username);
        let chatterlyFriend = await getChatterlyUsername(selectedFriend.name);

        console.log(chatterlyUsername, chatterlyFriend);
        console.log(await esamigos_Api(chatterlyUsername, chatterlyFriend));
        if (await esamigos_Api(chatterlyUsername, chatterlyFriend) == "aceptado") {
            console.log("sí");
            console.log(await usuarioNumero_Api(chatterlyUsername));
            await chat_api(await usuarioNumero_Api(chatterlyUsername), await usuarioNumero_Api(chatterlyFriend), input.value);
        }

        input.value = "";

        chat.scrollTop = chat.scrollHeight
    }
}

// Usar la función receiveMessages de la API para recibir todos los mensajes y crearlos.
// Esta función solo crea los nuevos mensajes.
async function getAllMessages(friendObject) {
    let jsonData = await receiveMessages(username, friendObject.name);
    let newMessages = jsonData.length - friendObject.messageNumber;

    for (let i = 0; i < newMessages; i++) {
        let sender = jsonData[friendObject.messageNumber].SENDER;
        let msg = jsonData[friendObject.messageNumber].MSG;
        let seen = jsonData[friendObject.messageNumber].SEEN;

        // Comprobar si el mensaje nuevo recibido es del amigo seleccionado.
        if (friendObject.name == selectedFriend.name) {
            createMessage(sender, msg);
        }
        // Por otro lado, añade 1 a los mensajes no vistos por el usuario actual.
        else if (seen == "0" && sender != username) {
            friendObject.addUnreadMessage();
        }

        friendObject.messageNumber += 1;
    }
}

// Comprobar cada 200 ms todos los mensajes de cada amigo.
setInterval(function () {
    for (i = 0; i < friendObjects.length; i++) {
        getAllMessages(friendObjects[i]);
    }
}, 200);