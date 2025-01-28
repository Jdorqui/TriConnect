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

function sendMessage(input, event) {
    if (event.key == "Enter" && input.value != "") {
        let formData = new FormData();
        formData.append("sender", username);
        formData.append("receiver", selectedFriend.name);
        formData.append("msg", input.value);

        input.value = "";

        fetch('../php/send_message.php', { method: "POST", body: formData });

        chat.scrollTop = chat.scrollHeight
    }
}

async function getAllMessages(friendObject) {
    let formData = new FormData();
    formData.append("sender", username);
    formData.append("receiver", friendObject.name);

    fetch('../php/receive_message.php', {
        method: "POST",
        body: formData
    })
        .then((response) => response.text())
        .then((data) => {
            let json_data = JSON.parse(data);
            // console.log(JSON.stringify(json_data, null, 2));
            let newMessages = JSON.parse(data).length - friendObject.messageNumber;

            //console.log(`DEBUG: ${JSON.stringify(json_data, null, 2)}`);
            // console.log(`DEBUG: ${newMessages}`);

            for (let i = 0; i < newMessages; i++) {
                let sender = json_data[friendObject.messageNumber][1];
                let msg = json_data[friendObject.messageNumber][3];

                console.log(`DEBUG-sender: ${sender}`);
                console.log(`DEBUG-username: ${username}`);
                console.log(`DEBUG-msg: ${msg}`);
                console.log(`DEBUG-json_data[friendObject.messageNumber][5]: ${json_data[friendObject.messageNumber][5]}`);

                if (friendObject.name == selectedFriend.name) {
                    createMessage(sender, msg);
                } else if (json_data[friendObject.messageNumber][5] == "0" && sender != username) {
                    console.log("inside");
                    friendObject.addUnreadMessage();
                }

                friendObject.messageNumber += 1;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

setInterval(function () {
    for (i = 0; i < friendObjects.length; i++) {
        getAllMessages(friendObjects[i]);
    }
}, 200);