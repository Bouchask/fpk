// frontend/admin/assets/js/layout.js
document.addEventListener('DOMContentLoaded', () => {
    const body = document.querySelector('body');
    const currentPage = window.location.pathname.split('/').pop();

    const layoutHTML = `
        <div class="min-h-screen flex">
            <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
                <div class="p-4 text-2xl font-bold border-b border-gray-700">Admin FPK</div>
                <nav class="mt-4">
                    <a href="index.html" class="block py-2 px-4 hover:bg-gray-700 ${currentPage === 'index.html' ? 'bg-gray-700' : ''}">Tableau de Bord</a>
                    <a href="demandes.html" class="block py-2 px-4 hover:bg-gray-700 ${currentPage === 'demandes.html' ? 'bg-gray-700' : ''}">Demandes d'Inscription</a>
                    <a href="etudiants.html" class="block py-2 px-4 hover:bg-gray-700 ${currentPage === 'etudiants.html' ? 'bg-gray-700' : ''}">Gérer les Étudiants</a>
                    <a href="professeurs.html" class="block py-2 px-4 hover:bg-gray-700 ${currentPage === 'professeurs.html' ? 'bg-gray-700' : ''}">Gérer les Professeurs</a>
                    <a href="#" id="logout-btn" class="block py-2 px-4 hover:bg-red-500">Déconnexion</a>
                </nav>
            </aside>
            <main id="main-content" class="flex-1 p-8 bg-gray-100"></main>
        </div>
    `;

    // Insérer la structure au début du body
    body.insertAdjacentHTML('afterbegin', layoutHTML);
    
    // Déplacer le contenu original de la page dans #main-content
    const mainContent = document.getElementById('main-content');
    const pageContent = document.getElementById('page-content');
    if (pageContent) {
        mainContent.appendChild(pageContent);
    }
    
    // Gérer la déconnexion
    document.getElementById('logout-btn').addEventListener('click', (e) => {
        e.preventDefault();
        localStorage.removeItem('adminToken');
        window.location.href = 'login.html';
    });
});