<?php
// backend/api/filiere.php
require_once 'auth.php';
require_once 'validator.php';
$pdo = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];
$user = getAuthenticatedUser();

if (!$user) {
    http_response_code(401);
    echo json_encode(['message'=>'Authentification requise.']);
    exit();
}

if ($method === 'GET') {
    // Gère la route /filiere/{id}/etudiants
    if ($id && $relation === 'etudiant') {
        $stmt = $pdo->prepare("SELECT id_etudiant, nom, prenom, cne FROM etudiant WHERE id_filiere = ? ORDER BY nom");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetchAll());
        return;
    }
    // ... (Ajoutez d'autres relations comme /filiere/{id}/module ici) ...

    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM filiere WHERE id_filiere = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
    } else {
        $stmt = $pdo->query("SELECT * FROM filiere ORDER BY nom_filiere");
        echo json_encode($stmt->fetchAll());
    }

} elseif ($method === 'POST') {
    $admin = requireAdmin();
    $data = (array) json_decode(file_get_contents('php://input'));
    
    $errors = validate($data, ['nom_filiere' => 'required']);
    if (!empty($errors)) { http_response_code(400); echo json_encode($errors); exit(); }

    $sql = "INSERT INTO filiere (nom_filiere, description, coordinateur_id) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data['nom_filiere'], $data['description'] ?? null, $data['coordinateur_id'] ?? null]);
    http_response_code(201);
    echo json_encode(['message' => 'Filière créée avec succès.']);

} elseif ($method === 'PUT' && isset($id)) {
    $admin = requireAdmin();
    // Logique pour modifier une filière...
    echo json_encode(['message' => 'Fonctionnalité de mise à jour à implémenter.']);

} elseif ($method === 'DELETE' && isset($id)) {
    $admin = requireAdmin();
    $stmt = $pdo->prepare("DELETE FROM filiere WHERE id_filiere = ?");
    $stmt->execute([$id]);
    echo json_encode(['message' => 'Filière supprimée avec succès.']);
}
?>