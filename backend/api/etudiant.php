<?php
// backend/api/etudiant.php
require_once 'auth.php';
require_once 'validator.php';
$pdo = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];
$user = getAuthenticatedUser();

if (!$user) { http_response_code(401); echo json_encode(['message'=>'Authentification requise.']); exit(); }

if ($method === 'POST') {
    $admin = requireAdmin();
    $data = (array) json_decode(file_get_contents('php://input'));
    
    // VALIDATION
    $errors = validate($data, ['nom' => 'required', 'prenom' => 'required', 'email' => 'required|email', 'cne' => 'required']);
    if (!empty($errors)) { http_response_code(400); echo json_encode($errors); exit(); }

    $hashed_password = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO etudiant (cne, nom, prenom, email, id_filiere, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data['cne'], $data['nom'], $data['prenom'], $data['email'], $data['id_filiere'], $hashed_password]);
    http_response_code(201); echo json_encode(['message' => 'Étudiant créé.']);

} elseif ($method === 'GET') {
    // SÉCURITÉ PAR RÔLE
    if ($id) { // Demande d'un étudiant spécifique
        if ($user->role === 'admin' || ($user->type === 'etudiant' && $user->id == $id)) {
            $stmt = $pdo->prepare("SELECT id_etudiant, cne, nom, prenom, email FROM etudiant WHERE id_etudiant = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch());
        } else { http_response_code(403); echo json_encode(['message'=>'Accès non autorisé.']); }
    } else { // Demande de la liste complète
        $admin = requireAdmin();
        $stmt = $pdo->query("SELECT id_etudiant, cne, nom, prenom, email FROM etudiant");
        echo json_encode($stmt->fetchAll());
    }
} elseif ($method === 'PUT' && isset($id)) {
    $admin = requireAdmin();
    // TODO: Ajouter la logique de modification
    echo json_encode(['message' => 'Fonctionnalité de mise à jour à implémenter.']);

} elseif ($method === 'DELETE' && isset($id)) {
    $admin = requireAdmin();
    $stmt = $pdo->prepare("DELETE FROM etudiant WHERE id_etudiant = ?");
    $stmt->execute([$id]);
    echo json_encode(['message' => 'Étudiant supprimé.']);
}
?>