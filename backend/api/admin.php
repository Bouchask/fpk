<?php
// backend/api/admin.php
require_once 'auth.php';
$pdo = getDBConnection();

$action = $id ?? null; // Ici, $id vient du routeur et sera 'login'

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'));

    if (!isset($data->email) || !isset($data->mot_de_passe)) {
        http_response_code(400);
        echo json_encode(['message' => 'Email ou mot de passe manquant.']);
        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM administrateur WHERE email = ?");
    $stmt->execute([$data->email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($data->mot_de_passe, $admin['mot_de_passe'])) {
        // Le mot de passe est correct, on génère un token
        $token = createToken($admin);
        echo json_encode(['message' => 'Connexion réussie.', 'token' => $token]);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Identifiants incorrects.']);
    }
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Action non valide.']);
}
?>