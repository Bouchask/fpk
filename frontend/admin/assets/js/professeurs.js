// frontend/admin/assets/js/professeurs.js

document.addEventListener('DOMContentLoaded', () => {
    const tableContainer = document.getElementById('professeurs-table');
    const modal = document.getElementById('add-prof-modal');
    const addProfBtn = document.getElementById('add-prof-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const addProfForm = document.getElementById('add-prof-form');

    // ... (la fonction loadProfesseurs est correcte et reste inchangée) ...
    async function loadProfesseurs() {
        tableContainer.innerHTML = '<p>Chargement...</p>';
        const professeurs = await fetchWithAuth('professeur').then(res => res.json());

        if (professeurs && professeurs.length > 0) {
            let table = `<table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr>
                    <th class="px-6 py-3 text-left">Nom Complet</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    </tr></thead><tbody>`;
            professeurs.forEach(p => {
                table += `<tr>
                    <td class="px-6 py-4">${p.prenom} ${p.nom}</td>
                    <td class="px-6 py-4">${p.email}</td>
                </tr>`;
            });
            table += `</tbody></table>`;
            tableContainer.innerHTML = table;
        } else {
            tableContainer.innerHTML = '<p>Aucun professeur trouvé.</p>';
        }
    }

    // ... (la gestion de la modale est correcte et reste inchangée) ...
    addProfBtn.addEventListener('click', () => modal.classList.add('active'));
    cancelBtn.addEventListener('click', () => modal.classList.remove('active'));

    // Gérer la soumission du formulaire de création
    addProfForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const newProf = {
            nom: document.getElementById('prof-nom').value,
            prenom: document.getElementById('prof-prenom').value,
            email: document.getElementById('prof-email').value,
            // CORRECTION ICI : 'password' au lieu de 'mot_de_passe'
            password: document.getElementById('prof-password').value,
        };

        const response = await fetchWithAuth('professeur', {
            method: 'POST',
            body: JSON.stringify(newProf)
        });

        if (response.ok) {
            modal.classList.remove('active');
            addProfForm.reset();
            loadProfesseurs(); // Recharger la liste
        } else {
            const errorData = await response.json();
            // Affiche une alerte plus détaillée si le backend renvoie des erreurs de validation
            let alertMessage = "Erreur lors de la création du professeur.";
            if (typeof errorData === 'object' && errorData !== null) {
                alertMessage += "\n\nDétails:\n" + Object.entries(errorData).map(([field, messages]) => `- ${field}: ${messages.join(', ')}`).join('\n');
            } else if (errorData.message) {
                alertMessage = errorData.message;
            }
            alert(alertMessage);
        }
    });

    // Initialisation
    loadProfesseurs();
});