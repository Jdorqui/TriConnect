// Dependencias:
// - import.js

const SETTINGS_CHILDREN = document.getElementById('settings_div').children[1].children;
const USER_PROFILE_PIC_DIV = document.getElementById('user_profile_pic');
const INPUT = document.createElement('input');

function displaySetting(id) {
    for (let i = 0; i < SETTINGS_CHILDREN.length; i++) {
        let child = SETTINGS_CHILDREN[i];
        if (child.id.includes(id)) {
            child.style.display = '';
        } else {
            child.style.display = 'none';
        }
    }
}

USER_PROFILE_PIC_DIV.addEventListener('mouseenter', () => {
    document.getElementById('cambiar_text').style.display = 'flex';
});

USER_PROFILE_PIC_DIV.addEventListener('mouseleave', () => {
    document.getElementById('cambiar_text').style.display = 'none';
});

USER_PROFILE_PIC_DIV.addEventListener('click', () => {
    INPUT.click();
});

INPUT.type = 'file';
INPUT.accept = 'image/*'

INPUT.onchange = async (e) => {
    let file = e.target.files[0]
    let formData = new FormData()
    formData.append('profile_pic_input', file)

    await fetch('../php/change_profile_pic.php', {
        method: "POST",
        body: formData,
    });

    setProfilePic(`../../../../../uploads/${username}/profile_pic.png`);
}

function setProfilePic(dir) {
    let now = new Date().toLocaleTimeString();
    let userLoggedInTabImg = document.getElementById('user_logged_in_tab').children[0];
    userLoggedInTabImg.src = dir + "?" + now;
    document.getElementById('user_profile_pic').children[1].src = dir + "?" + now;
}