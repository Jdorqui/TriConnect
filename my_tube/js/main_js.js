let main = document.querySelector(".main");

let loginAPIWrapper = document.getElementById("mytube_login_API_wrapper");
let loginDiv = document.getElementById("login_div");
let registerDiv = document.getElementById("register_div");

// Mostrar la ventana de inicio de sesión (API).
function displayLoginAPIWrapper() {
    loginAPIWrapper.style.display = "";
    loginDiv.style.display = "";
    main.style.filter = "brightness(20%)";
}

// Cerrar la ventana de inicio de sesión (API).
function closeLoginAPIWrapper() {
    loginAPIWrapper.style.display = "none";
    main.style.filter = "brightness(100%)";
}

function showLoginDiv() {
    hideRegisterDiv();

    loginDiv.style.display = "";
}

function hideLoginDiv() {
    loginDiv.style.display = "none";
}

// Mostrar la ventana de registro.
function showRegisterDiv() {
    hideLoginDiv();

    registerDiv.style.display = "";
}

// Cerrar la ventana de registro.
function hideRegisterDiv() {
    registerDiv.style.display = "none";
}

function validateLoginForm() {
    event.preventDefault();

    fetch("../php/login.php", {
        method: "POST",
        body: new FormData(document.getElementById("login_form")),
    })
        .then((response) => response.text())
        .then((data) => {
            checkLoginErrors(data);
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function validateRegisterForm() {
    event.preventDefault();

    fetch("../php/sign_up.php", {
        method: "POST",
        body: new FormData(document.getElementById("register_form")),
    })
        .then((response) => response.text())
        .then((data) => {
            checkRegisterErrors(data);
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function checkRegisterErrors(data) {
    if (data.includes("ERROR-001")) {
        createNotification("El usuario ya existe.");
    } else if (data.includes("SUCCESS")) {
        location.reload();
    }

    // Debug.
    // alert(data);
}

function checkLoginErrors(data) {
    console.log(data);

    if (data.includes("ERROR-001")) {
        createNotification("El usuario no existe.");
    } else if (data.includes("ERROR-002")) {
        createNotification("La contraseña no es correcta.");
    } else if (data.includes("SUCCESS")) {
        location.reload();
    } else {
        alert(data)
    }
}

let notifications = document.getElementById("notifications");
async function createNotification(message) {
    if (!notifications.innerHTML.includes(message)) {
        notifications.innerHTML += "<div class='notification'>" + message + "</div>";
    }
}

setInterval(function () {
    if (notifications.lastElementChild) {
        notifications.lastElementChild.remove();
    }
}, 1000);

/*
function validateLoginForm() {
    event.preventDefault();

    fetch("../php/login.php", {
        method: "POST",
        body: new FormData(document.getElementById("login-form")),
    })
        .then((response) => response.text())
        .then((data) => {
            checkErrors(data);
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}
*/

let stream = null;
let audio = null;
let mixedStream = null;
let chunks = [];
let recorder = null;
let startButton = null;
let stopButton = null;
let downloadButton = null;
let recordedVideo = null;

// video stuff.
async function setupStream() {
    try {
        // bruh
        let videoDefaultConstraintString = '{"frameRate": 60\n}';
        videoConstraints = JSON.parse(videoDefaultConstraintString);

        stream = await navigator.mediaDevices.getDisplayMedia({
            video: videoConstraints,
        });

        audio = await navigator.mediaDevices.getDisplayMedia({
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                sampleRate: 44100,
            },
        });

        setupVideoFeedback();
    } catch (e) {
        console.error(e);
    }
}

function setupVideoFeedback() {
    if (stream) {
        const video = document.querySelector(".video");
        video.srcObject = stream;

        video.srcObject.getVideoTracks()[0].applyConstraints();

        video.play();
    } else {
        console.warn("NO STREAM.");
    }
}

/*
async function startRecording() {
    await setupStream();

    if (stream && audio) {
        mixedStream = new MediaStream([...stream.getTracks(), ...audio.getTracks()]);
        recorder = new MediaStream(mixedStream);
        recorder.ondataavailable = handleDataAvailable;
        recorder.onstop = handleStop;
        recorder.start(200);
    }
}
*/
