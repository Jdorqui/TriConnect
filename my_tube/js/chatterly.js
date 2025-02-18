async function getChatterlyUsername(username) {
    let formData = new FormData();
    formData.append('USERNAME', username);

    let fetchData = await fetch(`../php/get_chatterly_username.php?USERNAME=${username}`, {
        method: "POST",
        body: formData,
    });

    let data = await fetchData.json();
    return data.CHATTERLY_USERNAME;
}

async function getChatterlyLogin() {
    await $('body').append('<script type="text/javascript" src="http://10.3.5.106/PHP/TriConnect/Chatterly%20v0.5/javascript/js_registerAndLogin.js"></script>');
    await fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/html/index_Api.html", {
        method: 'POST',
        mode: 'cors'
    })
        .then(response => response.text())
        .then(data => {
            document.getElementById('chatterly_login').innerHTML = data;
            document.getElementById('chatterly_login').style.display = "";
            user_profile_pic_settings.style.display = "none"

            document.getElementById('btn-submit-login').addEventListener("click", async function () {
                let chatterlyUsername = document.getElementById('usuario-login').value;
                let pass = document.getElementById('password-login').value;

                console.log(chatterlyUsername);
                console.log(pass);

                let data = await login_Api(chatterlyUsername, pass);
                if (data.status === "success") {
                    await $.get(`../php/set_chatterly_username.php?USERNAME=${username}&CHATTERLY_USERNAME=${chatterlyUsername}`);
                    
                    document.getElementById('chatterly_login').style.display = "none";
                }
                else {
                    const errorMessage = document.getElementById("error-message");
                    errorMessage.textContent = data.message;

                    errorMessage.style.color = "#f7767a";
                    document.getElementById("ms3").style.color = "#f7767a";
                    document.getElementById("ms4").style.color = "#f7767a";

                    if (document.getElementById("ms3").textContent.includes(data.message) == false) {
                        document.getElementById("ms3").textContent = document.getElementById("ms3").textContent + ` - ${data.message}`;
                        document.getElementById("ms4").textContent = document.getElementById("ms4").textContent + ` - ${data.message}`;
                    }
                }
            });
        });
}

(async () => {
    await getChatterlyLogin();
})();
