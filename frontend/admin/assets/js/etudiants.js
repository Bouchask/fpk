// frontend/admin/assets/js/etudiants.js
document.addEventListener('DOMContentLoaded', () => {
    const filiereFilter = document.getElementById('filiere-filter');
    const tableContainer = document.getElementById('etudiants-table');

    // Charger les options de filières dans le filtre
    async function loadFiliereOptions() {
        const filieres = await fetchWithAuth('filiere').then(res => res.json());
        if(filieres) {
            filiereFilter.innerHTML = '<option value="">Toutes les filières</option>';
            filieres.forEach(f => {
                filiereFilter.innerHTML += `<option value="${f.id_filiere}">${f.nom_filiere}</option>`;
            });
        }
    }

    // Charger les étudiants
    async function loadEtudiants(filiereId = '') {
        tableContainer.innerHTML = '<p>Chargement...</p>';
        let endpoint = 'etudiant';
        if (filiereId) {
            // Idéalement, le backend devrait supporter le filtrage
            // ex: endpoint = `etudiant?filiere=${filiereId}`
            // Pour l'instant, on récupère tout et on filtre côté client (pas optimal pour beaucoup de données)
        }
        
        const etudiants = await fetchWithAuth(endpoint).then(res => res.json());

        // Filtrage côté client
        const filteredEtudiants = filiereId 
            ? etudiants.filter(e => e.id_filiere == filiereId) 
            : etudiants;

        if (filteredEtudiants && filteredEtudiants.length > 0) {
            let table = `<table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr>
                    <th class="px-6 py-3 text-left">Nom Complet</th>
                    <th class="px-6 py-3 text-left">CNE</th>
                    <th class="px-6 py-3 text-left">Email</th>
                </tr></thead><tbody>`;
            filteredEtudiants.forEach(e => {
                table += `<tr>
                    <td class="px-6 py-4">${e.prenom} ${e.nom}</td>
                    <td class="px-6 py-4">${e.cne}</td>
                    <td class="px-6 py-4">${e.email}</td>
                </tr>`;
            });
            table += `</tbody></table>`;
            tableContainer.innerHTML = table;
        } else {
            tableContainer.innerHTML = '<p>Aucun étudiant trouvé.</p>';
        }
    }

    document.getElementById('filter-btn').addEventListener('click', () => {
        loadEtudiants(filiereFilter.value);
    });

    // Initialisation
    loadFiliereOptions();
    loadEtudiants();
});