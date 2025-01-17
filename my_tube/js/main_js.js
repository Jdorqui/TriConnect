let main = document.querySelector(".main");
let loginDiv = document.querySelector(".login_div");
let userDiv = document.getElementById("user_div");
let passwordDiv = document.getElementById("password_div");
let registerDiv = document.querySelector(".register_div");

// Mostrar la ventana de inicio de sesión.
function displayLoginDiv() {
    userDiv.style.display = "";
    passwordDiv.style.display = "none";

    loginDiv.style.display = "";
    main.style.filter = "brightness(20%)";
}

// Cerrar la ventana de inicio de sesión.
function closeLoginDiv() {
    loginDiv.style.display = "none";
    main.style.filter = "brightness(100%)";
}

function showLoginDiv() {
    userDiv.style.display = "none";
    passwordDiv.style.display = "";
}

function validateUsername() {
    event.preventDefault();

    let usernameInput = document.getElementById("USERNAME");

    let form = new FormData();
    form.append(usernameInput.getAttribute("name"), usernameInput.value);

    console.log(form);

    fetch("../php/login.php", {
        method: "POST",
        body: form,
    })
        .then((response) => response.text())
        .then((data) => {
            console.log(data);
            checkErrors(data);
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function checkErrors(data) {
    document.getElementsByTagName("label")[0].innerHTML = "Usuario:";
    document.getElementsByTagName("label")[0].style.color = "black";

    document.getElementsByTagName("label")[1].innerHTML = "Contraseña:";
    document.getElementsByTagName("label")[1].style.color = "black";

    let currentLabel;
    if (data.includes("ERROR-001")) {
        currentLabel = document.getElementsByTagName("label")[0];
        currentLabel.innerHTML = "Usuario: (el usuario no existe)";
        currentLabel.style.color = "red";
    } else if (data.includes("ERROR-002")) {
        currentLabel = document.getElementsByTagName("label")[1];
        currentLabel.innerHTML = "Contraseña: (la contraseña no es correcta)";
        currentLabel.style.color = "red";
    } else if (data.includes("SUCCESS")) {
        window.location.href = '../php/bienvenida.php';
    }

    // Debug.
    // alert(data);
}

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


// Mostrar la ventana de registro.
function displayRegisterDiv() {
    registerDiv.style.display = "";
    main.style.filter = "brightness(20%)";
}

// Cerrar la ventana de registro.
function closeRegisterDiv() {
    registerDiv.style.display = "none";
    main.style.filter = "brightness(100%)";
}




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
