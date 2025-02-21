// const MYTUBE_IP = "172.25.170.9";
// const MYTUBE_IP = "192.168.1.137";
const CHATTERLY_IP = "http://10.3.5.106/PHP/TriConnect/Chatterly%20v0.5";
const DETO_IP = "http://10.3.5.118";
const DEPENDENCIES = [
    'api',
    'channel',
    `${CHATTERLY_IP}/javascript/js_registerAndLogin.js`,
    `${CHATTERLY_IP}/javascript/api.js`,
    'chatterly',
    'chat',
    'main',
    'search',
    'settings',
    `${DETO_IP}`
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