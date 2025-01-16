// /public/script.js

// Mostrar el formulario de edición de perfil
document.getElementById('edit-profile-btn').addEventListener('click', () => {
    document.getElementById('edit-profile-form').style.display = 'block';
    document.getElementById('edit-profile-btn').style.display = 'none';
});

// Cancelar la edición del perfil
document.getElementById('cancel-edit').addEventListener('click', () => {
    document.getElementById('edit-profile-form').style.display = 'none';
    document.getElementById('edit-profile-btn').style.display = 'block';
});

// Guardar los cambios del perfil
document.getElementById('edit-profile').addEventListener('submit', (e) => {
    e.preventDefault();

    // Obtener los nuevos valores del perfil
    const newName = document.getElementById('name').value;
    const newLocation = document.getElementById('location').value;

    // Actualizar el perfil con los nuevos valores
    document.getElementById('profile-name').textContent = newName;
    document.getElementById('profile-location').textContent = `Ubicación: ${newLocation}`;

    // Ocultar el formulario de edición
    document.getElementById('edit-profile-form').style.display = 'none';
    document.getElementById('edit-profile-btn').style.display = 'block';
});
