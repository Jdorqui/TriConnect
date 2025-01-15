let main = document.querySelector(".main");
let loginDiv = document.querySelector(".login_div");

// Mostrar la ventana de inicio de sesión.
function displayLoginDiv() {
    loginDiv.style.display = "";
    main.style.filter = "brightness(20%)";
}

// Cerrar la ventana de inicio de sesión.
function closeLoginDiv() {
    loginDiv.style.display = "none";
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
