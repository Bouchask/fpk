
<?php
// backend/api/filiere.php
$pdo = getDBConnection();

// Gère la route /filiere/{id}/etudiants
if ($id && $relation === 'etudiant') {
    $stmt = $pdo->prepare("SELECT id_etudiant, nom, prenom, cne FROM etudiant WHERE id_filiere = ? ORDER BY nom");
    $stmt->execute([$id]);
    $etudiants = $stmt->fetchAll();
    echo json_encode($etudiants);
    return;
}

if ($id) {
    // Récupérer une seule filière par son ID
    $stmt = $pdo->prepare("SELECT * FROM filiere WHERE id_filiere = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
} else {
    // Récupérer toutes les filières
    $stmt = $pdo->query("SELECT * FROM filiere ORDER BY nom_filiere");
    $item = $stmt->fetchAll();
}

if (!$item) {
    http_response_code(404);
    echo json_encode(['message' => 'Ressource non trouvée']);
    return;
}
echo json_encode($item);
?>