document.addEventListener('DOMContentLoaded', () => {
    const messageForm = document.getElementById('message-form');
    const messagesContainer = document.getElementById('messages');
    const receiverId = document.querySelector('input[name="receiver_id"]').value;

    // Función para cargar los mensajes
    function loadMessages() {
        fetch('chat.php?user_id=' + receiverId)  // Asegúrate de que 'chat.php' está bien referenciado
            .then(response => response.json())   // Procesar la respuesta como JSON
            .then(data => {
                // Limpiar los mensajes actuales
                messagesContainer.innerHTML = '';

                // Mostrar los nuevos mensajes
                data.messages.forEach(msg => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');

                    // Mostrar el contenido de cada mensaje
                    messageElement.innerHTML = `
                        <strong>${msg.sender}:</strong> 
                        <span>${msg.mensaje}</span> 
                        <em>(${msg.fecha_envio})</em>
                    `;

                    // Agregar el mensaje al contenedor de mensajes
                    messagesContainer.appendChild(messageElement);
                });

                // Hacer scroll automático hacia abajo para ver los nuevos mensajes
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            })
            .catch(error => console.error('Error al cargar los mensajes:', error));
    }

    // Cargar los mensajes al cargar la página
    loadMessages();

    // Cargar mensajes cada 2 segundos para actualizar en tiempo real
    setInterval(loadMessages, 2000); // Intervalo de actualización de 2 segundos

    // Enviar el mensaje cuando se envíe el formulario
    messageForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de forma tradicional

        const messageInput = document.querySelector('textarea[name="message"]');
        const message = messageInput.value.trim();

        if (message === '') return; // No enviar si el mensaje está vacío

        // Enviar el mensaje mediante AJAX
        fetch('chat.php', {
            method: 'POST',
            body: new URLSearchParams({
                'message': message,
                'receiver_id': receiverId
            })
        })
        .then(response => response.text())
        .then(() => {
            messageInput.value = ''; // Limpiar el campo de texto
            loadMessages(); // Recargar los mensajes
        })
        .catch(error => console.error('Error al enviar el mensaje:', error));
    });
});
