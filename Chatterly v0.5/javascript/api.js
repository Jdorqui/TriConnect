function login_Api(usuario, password)
{
    var usuario;
    var password;

    fetch("https://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/login.php", 
    {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `usuario=${encodeURIComponent(usuario)}&password=${encodeURIComponent(password)}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") 
            {
                console.log("success");
                return data;
            } 
            else 
            {
                errorMessage.textContent = data.message;
                console.log("success'nt");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
}