// frontend/admin/assets/js/demandes.js
document.addEventListener('DOMContentLoaded', () => {
    const listContainer = document.getElementById('demandes-list');

    async function loadDemandes() {
        listContainer.innerHTML = '<p>Chargement des demandes...</p>';
        
        const demandes = await fetchWithAuth('demande').then(res => res.json());

        // On ne garde que les demandes "En attente"
        const pendingDemandes = demandes.filter(d => d.statut_demande === 'En attente');
        
        if (!pendingDemandes || pendingDemandes.length === 0) {
            listContainer.innerHTML = '<p class="text-gray-500">Aucune nouvelle demande à traiter.</p>';
            return;
        }

        listContainer.innerHTML = ''; // Vider la liste
        pendingDemandes.forEach(demande => {
            const demandeCard = document.createElement('div');
            demandeCard.className = 'flex items-center justify-between p-4 border-b';
            demandeCard.innerHTML = `
                <div>
                    <p class="font-semibold">Demande N°${demande.id_demande}</p>
                    <p class="text-sm text-gray-600">${demande.description} (Par étudiant ID: ${demande.id_etudiant})</p>
                </div>
                <div class="flex space-x-2">
                    <button data-id="${demande.id_demande}" data-action="Traitée" class="approve-btn bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Approuver</button>
                    <button data-id="${demande.id_demande}" data-action="Rejetée" class="reject-btn bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Rejeter</button>
                </div>
            `;
            listContainer.appendChild(demandeCard);
        });
    }

    // Gérer les clics sur les boutons d'approbation/rejet
    listContainer.addEventListener('click', async (e) => {
        if (e.target.classList.contains('approve-btn') || e.target.classList.contains('reject-btn')) {
            const demandeId = e.target.dataset.id;
            const newStatus = e.target.dataset.action;
            
            e.target.textContent = '...'; // Feedback visuel
            e.target.disabled = true;

            const response = await fetchWithAuth(`demande/${demandeId}`, {
                method: 'PUT',
                body: JSON.stringify({ statut_demande: newStatus })
            });

            if (response.ok) {
                // Succès, recharger la liste pour voir les changements
                loadDemandes(); 
            } else {
                alert('Erreur lors de la mise à jour de la demande.');
                e.target.textContent = newStatus === 'Traitée' ? 'Approuver' : 'Rejeter';
                e.target.disabled = false;
            }
        }
    });

    // Chargement initial
    loadDemandes();
});