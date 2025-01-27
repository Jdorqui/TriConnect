let messageNumber = 0;
function refresh() {
    messageNumber = 0;
    document.getElementById("chat").innerHTML = "";
}

let receiverName = "";
function changeChat(friend) {
    receiverName = friend.childNodes[3].innerHTML.trim();

    let userHeader = document.getElementById("user_header");
    userHeader.innerHTML = `<img class="every_user_image" src="../img/profile_pic_example.jpg">
                            <div>
                                ${receiverName}
                            </div>`;

    refresh();
}

function createFriendsDivs() {
    let container = document.getElementById("friend_navbar");

    for (let i = 0; i < friendsArray.length; i++) {
        container.innerHTML += `<div onclick="changeChat(this)" style="position: relative">
                                    <img class="every_user_image" src="../img/profile_pic_example.jpg">
                                    <div>
                                        ${friendsArray[i][0]}
                                    </div>
                                    <div class="new_messages_each_friend">
                                        2
                                    </div>
                                </div>`;
    }
}

createFriendsDivs();

if (friendsArray.length > 0) {
    let a = document.getElementById("friend_navbar").children[1];
    console.log(a);

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

function getAllMessages() {
    let formData = new FormData();
    formData.append("sender", username);
    formData.append("receiver", receiverName);

    fetch(`../php/receive_message.php`, {
        method: "POST",
        body: formData
    })
        .then((response) => response.text())
        .then((data) => {
            let json_data = JSON.parse(data);
            // console.log(JSON.stringify(json_data, null, 2));

            let newMessages = JSON.parse(data).length - messageNumber;
            // console.log(newMessages);

            for (let i = 0; i < newMessages; i++) {
                let sender = json_data[messageNumber][0];
                let msg = json_data[messageNumber][2];
                createMessage(sender, msg);

                messageNumber++;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

setInterval(function () {
    getAllMessages();
}, 200);