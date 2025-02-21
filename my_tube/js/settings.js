// Dependencias:
// - import.js

const SETTINGS_CHILDREN = document.getElementById('settings_div').children[1].children;

function displaySetting(id) {
    for (let i = 0; i < SETTINGS_CHILDREN.length; i++) {
        let child = SETTINGS_CHILDREN[i];

        console.log(child.id);
        console.log(id);

        if (child.id.includes(id)) {
            child.style.display = '';
        } else {
            child.style.display = 'none';
        }
    }
}