search_input.addEventListener("keyup", (event) => {
    search(search_input.value, event);
});

function search(searchQuery, event) {
    if (/Key[A-Z0-9]/.test(event.code) || event.code == "Enter") {
        display('search');
        // fakeLoading();

        let formData = new FormData();
        formData.append("search_query", searchQuery);

        fetch('../php/receive_channels_and_videos.php', {
            method: "POST",
            body: formData
        })
            .then((response) => response.text())
            .then((data) => {
                console.log("SEARCH: " + data);
                let array = JSON.parse(data);
                console.log("ARRAY: " + array);


                channels_main_div.innerHTML = '';
                for (let i = 0; i < array.length; i++) {
                    createChannelDiv(array[i]);
                }
            });
    }

}

// 1 segundo de carga.
function fakeLoading() {

}

function createChannelDiv(channel) {
    channels_main_div.innerHTML += `
        <div>
            <img class="search_user_image" src="../img/logged_out_profile_pic.jpg">
            <div>
                <div>${channel}</div>
                <div>subs</div>
            </div>
        </div>`;
}