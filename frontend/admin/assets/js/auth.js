// frontend/admin/assets/js/auth.js

const API_BASE_URL = 'http://localhost:8000/backend/api';
const token = localStorage.getItem('adminToken');

// Si on n'est pas sur la page de login et qu'il n'y a pas de token, on redirige
if (!token && window.location.pathname.indexOf('login.html') === -1) {
    window.location.href = 'login.html';
}

// Fonction pour faire des appels API authentifiés
async function fetchWithAuth(endpoint, options = {}) {
    const headers = {
        'Content-Type': 'application/json',
        ...options.headers,
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const response = await fetch(`${API_BASE_URL}/${endpoint}`, { ...options, headers });

    if (response.status === 401 || response.status === 403) {
        // Si le token est invalide ou expiré, on déconnecte
        localStorage.removeItem('adminToken');
        window.location.href = 'login.html';
    }
    
    return response;
}