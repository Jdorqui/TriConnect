// DONE.
function refresh() {
    for (let i = 0; i < messageNumber.length; i++) {
        messageNumber[i] = 0;
    }

    document.getElementById("chat").innerHTML = "";
}

// DONE.
let receiverName = "";
function changeChat(friend) {
    receiverName = friend.children[1].innerHTML.trim();

    let userHeader = document.getElementById("user_header");
    userHeader.innerHTML = `<img class="every_user_image" src="../img/profile_pic_example.jpg">
                            <div>
                                ${receiverName}
                            </div>`;

    refresh();

    let formData = new FormData();
    formData.append("sender", username);
    formData.append("receiver", receiverName);

    fetch(`../php/read_message.php`, {
        method: "POST",
        body: formData
    })
        .then((response) => response.text())
        .then((data) => {
            friend.children[2].innerHTML = 0;
            friend.children[2].style.display = "none";

            console.log(data);
        })
}

function getFriendIdFromName(friendName) {
    let newMessagesFriendsChatTab = document.getElementById("friend_navbar").children;
    for (let i = 1; i < newMessagesFriendsChatTab.length; i++) {
        if (newMessagesFriendsChatTab[i].children[1].innerHTML.trim() == friendName) {
            return i - 1;
        }
    }
}

// DONE.
let messageNumber = [];
function createFriendsDivs() {
    let container = document.getElementById("friend_navbar");

    for (let i = 0; i < friendsArray.length; i++) {
        container.innerHTML += `<div id=friend_${i} onclick="changeChat(this)" style="position: relative">
                                    <img class="every_user_image" src="../img/profile_pic_example.jpg">
                                    <div>
                                        ${friendsArray[i][0]}
                                    </div>
                                    <div class="new_messages_each_friend" style="display: none">
                                        0
                                    </div>
                                </div>`;

        messageNumber.push(0);
    }

    console.log(messageNumber);
}

createFriendsDivs();

if (friendsArray.length > 0) {
    let a = document.getElementById("friend_navbar").children[1];

    changeChat(a);
}

function sendMessage(input, event) {
    if (event.key == "Enter" && input.value != "") {
        let formData = new FormData();
        formData.append("sender", username);
        formData.append("receiver", receiverName);
        formData.append("msg", input.value);

        input.value = "";

        fetch(`../php/send_message.php`, {
            method: "POST",
            body: formData
        })

        document.getElementById("chat").scrollTop = document.getElementById("chat").scrollHeight
    }
}

// DONE.
function createMessage(sender, msg) {
    let chat = document.getElementById("chat");
    let before = chat.innerHTML;

    let style = "";
    let colorStyle = ""
    if (sender == username) {
        style = "margin-left: auto"
        colorStyle = "background-color: rgba(255, 0, 0, 0.267);"
    } else {
        style = "margin-right: auto"
        colorStyle = "background-color: rgba(65, 65, 65, 0.27);";
    }

    chat.innerHTML = `
                    <div class="message_body" style="${style}">
                        <img class="every_user_image" src="../img/profile_pic_example.jpg">
                        <div style="${colorStyle}">
                            <div>${sender}</div>
                            <div class="message">
                                ${msg}
                            </div>
                        </div>
                    </div>`;

    chat.innerHTML += before;
}

//DONE.
function updateUnreadMessages(sender) {
    let newMessagesFriendsChatTab = document.getElementById("friend_navbar").children;
    for (let i = 1; i < newMessagesFriendsChatTab.length; i++) {
        console.log("check: " + newMessagesFriendsChatTab[i].children[1].innerHTML.trim());
        console.log("sender: " + sender);
        if (newMessagesFriendsChatTab[i].children[1].innerHTML.trim() == sender && receiverName != sender) {
            let newMessagesSingleFriendChatTab = newMessagesFriendsChatTab[i].children[2]

            let actualUnreadMessages = parseInt(newMessagesSingleFriendChatTab.innerHTML) + 1;
            console.log("AUM: " + actualUnreadMessages);
            if (actualUnreadMessages > 99) {
                newMessagesSingleFriendChatTab.innerHTML = "99+";
                newMessagesSingleFriendChatTab.style.display = ""
                newMessagesSingleFriendChatTab.style.fontSize = "0.9vw";
            } else if (actualUnreadMessages > 0) {
                newMessagesSingleFriendChatTab.innerHTML = actualUnreadMessages;
                newMessagesSingleFriendChatTab.style.display = ""
            } else {
                newMessagesSingleFriendChatTab.innerHTML = 0;
                newMessagesSingleFriendChatTab.style.display = "none"
            }

            break;
        }
    }
}

async function getAllMessages(receiver) {
    let formData = new FormData();
    formData.append("sender", username);
    formData.append("receiver", receiver);

    fetch(`../php/receive_message.php`, {
        method: "POST",
        body: formData
    })
        .then((response) => response.text())
        .then((data) => {
            let json_data = JSON.parse(data);
            // console.log(JSON.stringify(json_data, null, 2));
            let msgN = messageNumber[getFriendIdFromName(receiver)];

            let newMessages = JSON.parse(data).length - msgN;

            // console.log(newMessages);

            for (let i = 0; i < newMessages; i++) {
                console.log(messageNumber);

                let sender = json_data[msgN][1];
                let msg = json_data[msgN][3];
                if (json_data[msgN][5] == "0") {
                    updateUnreadMessages(sender);
                }

                if (receiver == receiverName) {
                    createMessage(sender, msg);
                }

                messageNumber[getFriendIdFromName(receiver)] = messageNumber[getFriendIdFromName(receiver)] + 1;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

setInterval(function () {
    for (i = 0; i < friendsArray.length; i++) {
        getAllMessages(friendsArray[i][0]);
    }
}, 200);