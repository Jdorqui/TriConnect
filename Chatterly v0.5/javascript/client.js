// CONFIGURACIÓN DE LA SALA Y USUARIO
const ROOM = generateRandomRoom();  // Genera una sala aleatoria
const USER = getCurrentUser();  // Obtiene el usuario actual desde el sistema de PHP
const DESTINATARIO_ID = getDestinatarioID();  // Obtiene el destinatario desde la variable destinatarioID

// URL del script PHP de señalización
const SIGNAL_URL = '../php/signal.php';

// Variables globales de la llamada
let localStream;
let peerConnection;
let callTimerInterval;
let callStartTime;

const rtcConfig = {
  iceServers: [
    { urls: "stun:stun.l.google.com:19302" }
  ]
};

function formatTime(seconds) {
  const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
  const secs = (seconds % 60).toString().padStart(2, '0');
  return `${mins}:${secs}`;
}

function startCallTimer() {
  callStartTime = Date.now();
  document.getElementById("call-timer").textContent = "00:00";
  callTimerInterval = setInterval(() => {
    const elapsedSeconds = Math.floor((Date.now() - callStartTime) / 1000);
    document.getElementById("call-timer").textContent = formatTime(elapsedSeconds);
  }, 1000);
}

function stopCallTimer() {
  clearInterval(callTimerInterval);
  document.getElementById("call-timer").textContent = "00:00";
}

function createFakeStream() {
  const canvas = document.createElement('canvas');
  canvas.width = 640;
  canvas.height = 480;
  const ctx = canvas.getContext('2d');
  ctx.fillStyle = 'black';
  ctx.fillRect(0, 0, canvas.width, canvas.height);
  const videoStream = canvas.captureStream(15);
  const audioContext = new (window.AudioContext || window.webkitAudioContext)();
  const oscillator = audioContext.createOscillator();
  const dst = oscillator.connect(audioContext.createMediaStreamDestination());
  oscillator.start();
  const audioTrack = dst.stream.getAudioTracks()[0];
  audioTrack.enabled = false;
  const fakeStream = new MediaStream();
  videoStream.getVideoTracks().forEach(track => fakeStream.addTrack(track));
  fakeStream.addTrack(audioTrack);
  return fakeStream;
}

function sendSignal(message) {
  console.log("Enviando señal:", message); // Debug de la señal que se va a enviar
  fetch(SIGNAL_URL + '?room=' + ROOM + '&user=' + USER + '&destinatarioID=' + DESTINATARIO_ID, {
    method: 'POST',
    body: JSON.stringify(message),
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    console.log("Señal enviada:", data); // Debug de la respuesta de la señal
  })
  .catch(error => console.error("Error al enviar señal:", error));
}

function pollSignals() {
  fetch(SIGNAL_URL + '?room=' + ROOM + '&user=' + USER + '&destinatarioID=' + DESTINATARIO_ID)
    .then(response => {
      if (!response.ok) {
        throw new Error('Error en la respuesta del servidor: ' + response.statusText);
      }
      return response.text();  // Cambiar a text() para ver el contenido completo
    })
    .then(responseText => {
      try {
        const messages = JSON.parse(responseText);  // Intentamos parsear el texto como JSON
        messages.forEach(message => {
          handleSignalMessage(message);
        });
      } catch (e) {
        console.error("Error al procesar la respuesta JSON:", e, responseText);
      }
      setTimeout(pollSignals, 1000);
    })
    .catch(error => {
      console.error("Error al obtener señales:", error);
      setTimeout(pollSignals, 3000);
    });
}

async function handleSignalMessage(message) {
  console.log("Señal recibida:", message); // Debug de la señal recibida
  switch (message.event_type) {
    case 'offer':
      console.log("Procesando oferta..."); // Debug de la oferta recibida
      if (!peerConnection) {
        // En el receptor (callee): muestra la interfaz de llamada y los botones de aceptar/rechazar
        console.log("Iniciando llamada en el lado del receptor...");
        document.querySelector('.chat-header').style.display = 'none';
        document.querySelector('.chat-messages').style.display = 'none';
        document.querySelector('.chat-input').style.display = 'none';
        document.querySelector('.emoji-container').style.display = 'none';
        document.getElementById('chat-separator').style.display = 'none';
        document.getElementById("call-container").style.display = 'block';
        startCallTimer();

        try {
          localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        } catch (error) {
          console.warn("No se pudo acceder a cámara/micrófono. Se usará stream falso.", error);
          localStream = createFakeStream();
        }
        document.getElementById("localVideo").srcObject = localStream;
        peerConnection = new RTCPeerConnection(rtcConfig);
        localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));
        peerConnection.ontrack = (event) => {
          console.log("Stream remoto recibido:", event); // Debug del stream remoto
          document.getElementById("remoteVideo").srcObject = event.streams[0];
        };
        peerConnection.onicecandidate = (event) => {
          if (event.candidate) {
            console.log("Enviando candidato ICE:", event.candidate); // Debug de candidato ICE
            sendSignal({
              type: 'candidate',
              candidate: event.candidate
            });
          }
        };
        // Establecer la oferta recibida como remote description
        try {
          const offerObj = JSON.parse(message.message).offer;
          await peerConnection.setRemoteDescription(new RTCSessionDescription(offerObj));
        } catch (e) {
          console.error("Error al establecer la descripción remota:", e);
        }
      }
      break;
    case 'answer':
      console.log("Procesando respuesta..."); // Debug de la respuesta recibida
      try {
        const answerObj = JSON.parse(message.message).answer;
        await peerConnection.setRemoteDescription(new RTCSessionDescription(answerObj));
      } catch (error) {
        console.error("Error al procesar la respuesta:", error);
      }
      break;
    case 'candidate':
      console.log("Procesando candidato ICE..."); // Debug del candidato ICE
      try {
        const candidateObj = JSON.parse(message.message).candidate;
        await peerConnection.addIceCandidate(new RTCIceCandidate(candidateObj));
      } catch (error) {
        console.error("Error al agregar candidato:", error);
      }
      break;
    case 'reject':
      console.log("Llamada rechazada por el partner.");
      alert("La llamada fue rechazada.");
      hangUp();
      break;
    default:
      console.log("Señal desconocida:", message); // Debug para señales desconocidas
  }
}

async function callfriend() {
  document.querySelector('.chat-header').style.display = 'none';
  document.querySelector('.chat-messages').style.display = 'none';
  document.querySelector('.chat-input').style.display = 'none';
  document.querySelector('.emoji-container').style.display = 'none';
  document.getElementById('chat-separator').style.display = 'none';
  document.getElementById("call-container").style.display = 'block';
  try {
    localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
  } catch (error) {
    console.warn("No se pudo acceder a cámara/micrófono. Se usará stream falso.", error);
    localStream = createFakeStream();
  }
  document.getElementById("localVideo").srcObject = localStream;
  startCallTimer();
  peerConnection = new RTCPeerConnection(rtcConfig);
  localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));
  peerConnection.ontrack = (event) => {
    document.getElementById("remoteVideo").srcObject = event.streams[0];
  };
  peerConnection.onicecandidate = (event) => {
    if (event.candidate) {
      sendSignal({
        type: 'candidate',
        candidate: event.candidate
      });
    }
  };
  try {
    const offer = await peerConnection.createOffer({
      offerToReceiveAudio: 1,
      offerToReceiveVideo: 1
    });
    await peerConnection.setLocalDescription(offer);
    sendSignal({
      type: 'offer',
      offer: offer
    });
  } catch (error) {
    console.error("Error al crear la oferta:", error);
  }
}

async function acceptCall() {
  document.getElementById("accept-call-btn").style.display = 'none';
  document.getElementById("reject-call-btn").style.display = 'none';
  try {
    const answer = await peerConnection.createAnswer();
    await peerConnection.setLocalDescription(answer);
    sendSignal({
      type: 'answer',
      answer: answer
    });
  } catch (error) {
    console.error("Error al crear la respuesta:", error);
  }
}

function rejectCall() {
  sendSignal({ type: 'reject' });
  hangUp();
}

function hangUp() {
  document.querySelector('.chat-header').style.display = 'block';
  document.querySelector('.chat-messages').style.display = 'block';
  document.querySelector('.chat-input').style.display = 'block';
  document.querySelector('.emoji-container').style.display = 'block';
  document.getElementById('chat-separator').style.display = 'block';
  document.getElementById("call-container").style.display = 'none';
  stopCallTimer();
  if (peerConnection) {
    peerConnection.close();
    peerConnection = null;
  }
  if (localStream) {
    localStream.getTracks().forEach(track => track.stop());
    localStream = null;
  }
}

function generateRandomRoom() {
  return 'room_' + Math.random().toString(36).substring(7);  // Genera una sala aleatoria
}

function getCurrentUser() {
  return "<?php echo $_SESSION['usuario']; ?>";  // Obtiene el nombre de usuario desde PHP
}

function getDestinatarioID() {
  return "<?php echo $_GET['destinatarioID']; ?>";  // Obtiene el destinatario desde la URL o la variable de PHP
}

// Inicia el polling de señales cuando se carga el script
pollSignals();
