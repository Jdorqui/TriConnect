// Actualiza las conversaciones
function loadConversations() {
    fetch('php/mensajes.php?conversation_id=' + conversationId)
        .then(response => response.json())
        .then(data => {
            const chatbox = document.getElementById('chatbox');
            data.messages.forEach(msg => {
                chatbox.innerHTML += `<p><strong>${msg.sender}</strong>: ${msg.mensaje} <em>${msg.fecha_envio}</em></p>`;
            });
        });
}

// Enviar un mensaje
document.getElementById('messageForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const message = document.getElementById('message').value;
    const receiverId = document.getElementById('receiver_id').value;

    fetch('php/enviar_mensaje.php', {
        method: 'POST',
        body: JSON.stringify({
            mensaje: message,
            receiver_id: receiverId
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(() => {
        loadConversations();
    });
});

loadConversations();
