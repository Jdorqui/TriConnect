// const MYTUBE_IP = "172.25.170.9";
const MYTUBE_IP = "192.168.1.137";
// const MYTUBE_IP = "10.3.5.111";
const CHATTERLY_IP = "10.3.5.106";
const DETO_IP = "10.3.5.118";
const DEPENDENCIES = [
    'api',
    'channel',
    `http://${CHATTERLY_IP}/PHP/TriConnect/Chatterly%20v0.5/javascript/js_registerAndLogin.js`,
    `http://${CHATTERLY_IP}/PHP/TriConnect/Chatterly%20v0.5/javascript/api.js`,
    'chatterly',
    'chat',
    'main',
    'search',
    `http://${DETO_IP}`
];

DEPENDENCIES.forEach(async (dependency) => {
    if (!dependency.includes('http')) {
        $('body').append(`<script type="text/javascript" src="../js/${dependency}.js"></script>`);
    } else {
        await new Promise(function () {
            setTimeout(function () {
                $('body')
                    .append(`<script type="text/javascript" src="${dependency}"></script>`);
            }, 200);
        });
    }
});