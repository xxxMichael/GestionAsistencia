document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('../models/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Inicio realizado con éxito');

            if (data.role === 'administrador') {
                window.location.href = '../index.php';
            } else {
                window.location.href = 'interfaces/GestionInOut.php';
            }
        } else {
            const errorMessage = document.getElementById('error-message');
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'Usuario o contraseña incorrectos';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
