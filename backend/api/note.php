<?php
// backend/api/note.php
require_once 'auth.php';
$pdo = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];
$user = getAuthenticatedUser();

if (!$user) {
    http_response_code(401);
    echo json_encode(['message'=>'Authentification requise.']);
    exit();
}

if ($method === 'GET') {
    if ($user->type === 'etudiant') {
        // Un étudiant ne peut voir que ses propres notes
        $sql = "SELECT m.nom_module, n.note_cc, n.note_examen, n.note_finale, n.session 
                FROM note n
                JOIN inscription i ON n.id_inscription = i.id_inscription
                JOIN module m ON n.id_module = m.id_module
                WHERE i.id_etudiant = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user->id]);
        echo json_encode($stmt->fetchAll());

    } elseif ($user->type === 'professeur') {
        // Un professeur ne peut voir que les notes des modules dont il est responsable
        $sql = "SELECT e.nom, e.prenom, m.nom_module, n.note_finale
                FROM note n
                JOIN module m ON n.id_module = m.id_module
                JOIN inscription i ON n.id_inscription = i.id_inscription
                JOIN etudiant e ON i.id_etudiant = e.id_etudiant
                WHERE m.id_professeur_responsable = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user->id]);
        echo json_encode($stmt->fetchAll());

    } elseif ($user->role === 'superadmin' || $user->role === 'gestionnaire') {
        // L'admin peut tout voir
        $stmt = $pdo->query("SELECT * FROM note");
        echo json_encode($stmt->fetchAll());
        
    } else {
        http_response_code(403);
        echo json_encode(['message' => 'Accès non autorisé.']);
    }

} elseif (in_array($method, ['POST', 'PUT'])) {
    // Seul un professeur ou un admin peut ajouter/modifier une note
    if ($user->type !== 'professeur' && $user->role !== 'superadmin' && $user->role !== 'gestionnaire') {
        http_response_code(403);
        echo json_encode(['message' => 'Accès non autorisé.']);
        exit();
    }
    // TODO: Ajouter la logique pour créer ou modifier une note
    echo json_encode(['message' => 'Fonctionnalité de gestion des notes à implémenter.']);
}
?>