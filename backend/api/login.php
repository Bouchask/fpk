<?php
// backend/api/login.php
require_once 'auth.php';
$pdo = getDBConnection();

$data = json_decode(file_get_contents('php://input'));
if (!isset($data->email, $data->password, $data->type)) {
    http_response_code(400); echo json_encode(['message' => 'Données manquantes.']); exit();
}

$table = '';
switch ($data->type) {
    case 'admin': $table = 'administrateur'; break;
    case 'etudiant': $table = 'etudiant'; break;
    case 'professeur': $table = 'professeur'; break;
    default: http_response_code(400); echo json_encode(['message' => 'Type d\'utilisateur invalide.']); exit();
}

$stmt = $pdo->prepare("SELECT * FROM $table WHERE email = ?");
$stmt->execute([$data->email]);
$user = $stmt->fetch();

if ($user && password_verify($data->password, $user['mot_de_passe'])) {
    $token = createToken($user, $data->type);
    echo json_encode(['message' => 'Connexion réussie.', 'token' => $token, 'user' => ['role' => $user['role'] ?? $data->type]]);
} else {
    http_response_code(401);
    echo json_encode(['message' => 'Identifiants incorrects.']);
}
?>