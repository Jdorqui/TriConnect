const userType = sessionStorage.getItem('userType') || 'guest'; 
let roomId = new URLSearchParams(window.location.search).get('room');

if (!roomId) {
    // Generar un roomId aleatorio si no existe
    roomId = 'room_' + Math.random().toString(36).substr(2, 9);
    console.log("Room generado automáticamente:", roomId);
}

let peerConnection;
const localVideo = document.getElementById("localVideo");
const remoteVideo = document.getElementById("remoteVideo");
const callContainer = document.getElementById("call-container");

// Establecemos los ID de emisor y receptor
const emitterId = 1;
const receiverId = 2;

function startCall() {
  // Ocultar los elementos innecesarios del chat
  document.getElementById("chat-messages").style.display = "none"; // Ocultar los mensajes del chat
  document.querySelector(".chat-input").style.display = "none"; // Ocultar la entrada de texto y los botones
  document.querySelector(".emoji-container").style.display = "none";
  document.querySelector(".chat-header").style.display = "none";
  document.getElementById("chat-separator").style.display = "none";
  
  callContainer.style.display = "block"; 

  peerConnection = new RTCPeerConnection();
  navigator.mediaDevices.getUserMedia({ video: true, audio: true })
      .then(stream => {
          localVideo.srcObject = stream;
          stream.getTracks().forEach(track => peerConnection.addTrack(track, stream));
          return peerConnection.createOffer();
      })
      .then(offer => {
          peerConnection.setLocalDescription(offer);
          sendSignal('offer', offer, emitterId, receiverId);
      })
      .catch(error => console.error("Error al iniciar la llamada:", error));
}


function acceptCall() {
    $.post('signal.php', { action: 'accept_call', room: roomId, emitterId: emitterId, receiverId: receiverId }, function(response) {
        console.log("Llamada aceptada:", response);
        peerConnection = new RTCPeerConnection();
        navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then(stream => {
                localVideo.srcObject = stream;
                stream.getTracks().forEach(track => peerConnection.addTrack(track, stream));
            });
    });
    document.getElementById("accept-call-btn").style.display = "none";
    document.getElementById("reject-call-btn").style.display = "none";
}

function hangUp() {
  if (peerConnection) {
      peerConnection.close();
  }
  
  // Ocultar el contenedor de la llamada
  callContainer.style.display = "none";

  // Mostrar el chat nuevamente
  document.getElementById("chat-messages").style.display = "block"; // Mostrar los mensajes del chat
  document.querySelector(".chat-input").style.display = "block"; // Mostrar la entrada de texto y los botones
  document.querySelector(".emoji-container").style.display = "block";
  document.querySelector(".chat-header").style.display = "block";
  document.getElementById("chat-separator").style.display = "block";

  // Enviar la señal de colgar
  sendSignal('hangup', {}, emitterId, receiverId);
}

function sendSignal(type, data, emitterId, receiverId) {
    $.ajax({
        url: 'signal.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            type: type,
            data: data,
            room: roomId,
            user: userType,
            emitterId: emitterId,
            receiverId: receiverId
        }),
        success: function(response) {
            console.log("Señal enviada:", response);
        },
        error: function(xhr, status, error) {
            console.error("Error al enviar la señal:", error);
        }
    });
}

function receiveSignals() {
    if (!roomId) return; // Evitar errores si roomId no está definido

    $.ajax({
        url: 'signal.php',
        method: 'GET',
        data: { room: roomId, user: userType },
        success: function(response) {
            try {
                let messages = JSON.parse(response);
                if (Array.isArray(messages)) {
                    messages.forEach(message => {
                        processSignal(message);
                    });
                } else {
                    console.error("Respuesta inesperada:", messages);
                }
            } catch (e) {
                console.error("Error al procesar la respuesta JSON:", e);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al recibir señales:", error);
        }
    });
}

function processSignal(message) {
    switch (message.type) {
        case 'offer':
            if (message.emitterId === receiverId) {
                peerConnection.setRemoteDescription(new RTCSessionDescription(message.data));
                peerConnection.createAnswer().then(answer => {
                    peerConnection.setLocalDescription(answer);
                    sendSignal('answer', answer, emitterId, receiverId);
                });
            }
            break;
        case 'answer':
            if (message.receiverId === emitterId) {
                peerConnection.setRemoteDescription(new RTCSessionDescription(message.data));
            }
            break;
        case 'candidate':
            peerConnection.addIceCandidate(new RTCIceCandidate(message.data));
            break;
        case 'hangup':
            hangUp();
            break;
        default:
            console.warn("Tipo de señal desconocida:", message.type);
    }
}

setInterval(receiveSignals, 3000);
