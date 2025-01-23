function createFriendsDivs() {
    let container = document.getElementById("friend_navbar");

    for (let i = 0; i < friendsArray.length; i++) {
        container.innerHTML += `<div onclick="changeChat(this)">
                                    <img src="../img/profile_pic_example.jpg">
                                    <div>
                                        ${friendsArray[i][0]}
                                    </div>
                                </div>`;
    }
}

createFriendsDivs();