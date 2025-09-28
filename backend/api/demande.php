<?php
// backend/api/demande.php
require_once 'auth.php';
$pdo = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];
$user = getAuthenticatedUser();

// ... (logique GET et POST comme dans la version précédente) ...

if ($method === 'PUT' && isset($id)) {
    $admin = requireAdmin();
    $data = json_decode(file_get_contents('php://input'));
    if (!isset($data->statut_demande)) { /* ... erreur ... */ }

    // LOGIQUE MÉTIER AJOUTÉE
    if ($data->statut_demande === 'Traitée') {
        // 1. Récupérer les infos de la demande
        $stmt_demande = $pdo->prepare("SELECT id_etudiant, description FROM demande WHERE id_demande = ?");
        $stmt_demande->execute([$id]);
        $demande = $stmt_demande->fetch();
        
        // 2. Extraire l'ID de la filière depuis la description
        preg_match('/ID: (\d+)/', $demande['description'], $matches);
        $id_filiere = $matches[1] ?? null;

        if ($demande && $id_filiere) {
            // 3. Trouver le premier semestre de cette filière
            $stmt_semestre = $pdo->prepare("SELECT id_semestre FROM semestre WHERE id_filiere = ? ORDER BY numero_semestre ASC LIMIT 1");
            $stmt_semestre->execute([$id_filiere]);
            $semestre = $stmt_semestre->fetch();

            if($semestre) {
                // 4. Créer l'inscription !
                $stmt_ins = $pdo->prepare("INSERT INTO inscription (id_etudiant, id_semestre, statut) VALUES (?, ?, 'Inscrit') ON CONFLICT (id_etudiant, id_semestre) DO NOTHING");
                $stmt_ins->execute([$demande['id_etudiant'], $semestre['id_semestre']]);
            }
        }
    }

    // 5. Mettre à jour la demande
    $sql = "UPDATE demande SET statut_demande = ?, id_admin_traitant = ? WHERE id_demande = ?";
    $stmt_update = $pdo->prepare($sql);
    if ($stmt_update->execute([$data->statut_demande, $admin->id, $id])) {
        echo json_encode(['message' => 'Demande traitée et inscription créée.']);
    } else { /* ... erreur ... */ }
}
?>