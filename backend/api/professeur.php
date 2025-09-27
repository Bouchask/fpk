<?php
// backend/api/professeur.php
$pdo = getDBConnection();

// Gère la route /professeur/{id}/specialites
if ($id && $relation === 'specialite') {
    $sql = "SELECT s.id_specialite, s.nom_specialite
            FROM specialite s
            JOIN professeur_specialite ps ON s.id_specialite = ps.id_specialite
            WHERE ps.id_professeur = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $specialites = $stmt->fetchAll();
    echo json_encode($specialites);
    return;
}

if ($id) {
    // Récupérer un seul professeur par son ID
    $stmt = $pdo->prepare("SELECT * FROM professeur WHERE id_professeur = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
} else {
    // Récupérer tous les professeurs
    $stmt = $pdo->query("SELECT * FROM professeur ORDER BY nom");
    $item = $stmt->fetchAll();
}

if (!$item) {
    http_response_code(404);
    echo json_encode(['message' => 'Ressource non trouvée']);
    return;
}
echo json_encode($item);
?>