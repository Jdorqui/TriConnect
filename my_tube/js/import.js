// const BASE_DIR = "/opt/lampp/";
const BASE_DIR = "C:/xampp/";
const CHATTERLY_IP = "http://10.3.5.106/PHP/TriConnect/Chatterly%20v0.5";
const DETO_IP = "http://10.3.5.118";
const DEPENDENCIES = [
    'api',
    'channel',
    `${CHATTERLY_IP}/javascript/api.js`,
    'chatterly',
    'chat',
    'main',
    'search',
    'settings',
];

DEPENDENCIES.forEach(async (dependency) => {
    if (!dependency.includes('http')) {
        $('body').append(`<script type="text/javascript" src="../js/${dependency}.js"></script>`);
    } else {
        await new Promise(() => {
            setTimeout(() => {
                $('body')
                    .append(`<script type="text/javascript" src="${dependency}"></script>`);
            }, 100);
        });
    }
});