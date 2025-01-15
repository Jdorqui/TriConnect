document.addEventListener('DOMContentLoaded', function () {
    const messageForm = document.getElementById('message-form');
    const chatBox = document.getElementById('chat-box');

    if (messageForm) {
        messageForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(messageForm);

            fetch('ajax_messages.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  updateChat(data);
                  messageForm.reset();
              });
        });

        function fetchMessages() {
            const receiverId = document.querySelector('input[name="receiver_id"]').value;

            fetch('ajax_messages.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `receiver_id=${receiverId}`
            }).then(response => response.json())
              .then(data => updateChat(data));
        }

        function updateChat(messages) {
            chatBox.innerHTML = '';
            messages.forEach(message => {
                const messageElement = document.createElement('p');
                messageElement.innerHTML = `<strong>${message.sender_alias}:</strong> ${message.mensaje} <em>(${message.fecha_envio})</em>`;
                chatBox.appendChild(messageElement);
            });
        }

        setInterval(fetchMessages, 3000); // Actualizar cada 3 segundos
    }
});
