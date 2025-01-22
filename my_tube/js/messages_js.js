function createFriendDiv() {
    let container = document.getElementById("friend_navbar");

    for (let i = 0; i < friendsArray.length; i++) {
        container.innerHTML += `<div style="display: flex;" onclick="changeChat(this)">
                                    <img src="../img/profile_pic_example.jpg" id="logged_pic">
                                    <div>
                                        ${friendsArray[i][0]}
                                    </div>
                                </div>`;
    }
}

createFriendDiv();

function changeChat(friend) {
    console.log(friend.childNodes);

    let userHeader = document.getElementById("user_header");
    userHeader.innerHTML = `<img src="../img/profile_pic_example.jpg" id="logged_pic">
                            <div>
                                ${friend}
                            </div>`;
}


function sendMessage(input, event) {
    if (event.key == "Enter" && input.value != "") {
        let formData = new FormData();
        formData.append("sender", username);
        formData.append("receiver", "z");
        formData.append("msg", input.value);

        input.value = "";

        fetch(`../php/send_message.php`, {
            method: "POST",
            body: formData
        })
            .then((response) => response.text())
            .then((data) => {
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }
}

function createMessage(sender, msg) {
    let chat = document.getElementById("chat");
    let before = chat.innerHTML;
    chat.innerHTML = `
                    <div class="message_body">
                        <img src="../img/profile_pic_example.jpg" id="logged_pic">
                        <div style="background-color: blue; width: 100%;">
                            <div style="font-size: 25px;">${sender}</div>
                            <div class="message">
                                ${msg}
                            </div>
                        </div>
                    </div>`;
    chat.innerHTML += before;
}

let messageNumber = 0;
function getAllMessages() {
    let formData = new FormData();
    formData.append("sender", username);
    formData.append("receiver", "z");

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