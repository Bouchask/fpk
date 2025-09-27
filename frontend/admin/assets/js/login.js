// frontend/admin/assets/js/login.js
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('error-message');

    try {
        const response = await fetch('http://localhost:8000/backend/api/admin/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email, mot_de_passe: password })
        });

        const data = await response.json();

        if (response.ok) {
            localStorage.setItem('adminToken', data.token);
            window.location.href = 'index.html'; // Redirection vers le tableau de bord
        } else {
            errorMessage.textContent = data.message || 'Une erreur est survenue.';
        }
    } catch (error) {
        errorMessage.textContent = 'Erreur de connexion au serveur.';
    }
});