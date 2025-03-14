// Dependencias:
// - jQuery
// - api.js

let contentDiv = document.getElementById('content');
let contentDivChildren = contentDiv.children;

// Ocultar todos los divs del elemento 'content' excepto el primero 'home_div'.
for (let i = 1; i < contentDivChildren.length; i++) {
    contentDivChildren[i].style.display = 'none';
}

// Mostrar div dependiendo de la imagen clicada.
let divDisplaying = '';
function display(id) {
    divDisplaying = id;

    if ((id != 'home' && id != 'search') && username == '') {
        displayLoginAPIWrapper();
    } else {
        for (let i = 0; i < contentDivChildren.length; i++) {
            let child = contentDivChildren[i];
            if (child.id.includes(id)) {
                child.style.display = '';
            } else {
                child.style.display = 'none';
            }
        }
    }
}

// display('settings');
// search('', 'test');
display('chat');

async function logout() {
    await $.get("../php/logout.php");
    location.reload();
}