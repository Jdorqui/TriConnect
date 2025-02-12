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