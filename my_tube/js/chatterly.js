fetch("http://10.3.5.106/PHP/TriConnect/Chatterly v0.5/php/login.php", {
    method: "GET"
})
    .then((response) => response.text())
    .then((data) => {
        console.log(data);
    })
    .catch((error) => {
        console.error("Error:", error);
    });