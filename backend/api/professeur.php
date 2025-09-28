<?php
// backend/api/professeur.php
require_once 'auth.php';
require_once 'validator.php'; // AJOUTÉ
$pdo = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];
$user = getAuthenticatedUser(); // AJOUTÉ

// SÉCURITÉ : Seuls les utilisateurs connectés peuvent accéder à cette ressource
if (!$user) {
    http_response_code(401);
    echo json_encode(['message'=>'Authentification requise.']);
    exit();
}

if ($method === 'POST') {
    $admin = requireAdmin();
    $data = (array) json_decode(file_get_contents('php://input'));
    
    // VALIDATION AJOUTÉE
    $errors = validate($data, ['nom' => 'required', 'prenom' => 'required', 'email' => 'required|email', 'mot_de_passe' => 'required']);
    if (!empty($errors)) { http_response_code(400); echo json_encode($errors); exit(); }

    $hashed_password = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
    
    // CORRECTION DU BUG CRITIQUE : Ajout de la colonne mot_de_passe
    $sql = "INSERT INTO professeur (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$data['nom'], $data['prenom'], $data['email'], $hashed_password])) {
        http_response_code(201);
        echo json_encode(['message' => 'Professeur créé avec succès.']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Erreur lors de la création.']);
    }
} elseif ($method === 'GET') {
    // La logique GET existante est bonne
    if (isset($id) && isset($relation) && $relation === 'specialite') {
        // ...
    } elseif (isset($id)) {
        // ...
    } else {
        // ...
    }
    // ...
} elseif ($method === 'PUT' && isset($id)) {
    $admin = requireAdmin();
    $data = (array) json_decode(file_get_contents('php://input'));
    // TODO: Logique de mise à jour (UPDATE)
    echo json_encode(['message' => 'Professeur mis à jour.']);

} elseif ($method === 'DELETE' && isset($id)) {
    $admin = requireAdmin();
    $stmt = $pdo->prepare("DELETE FROM professeur WHERE id_professeur = ?");
    $stmt->execute([$id]);
    echo json_encode(['message' => 'Professeur supprimé.']);
}
?>