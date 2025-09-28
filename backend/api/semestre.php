<?php
// backend/api/semestre.php
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
    if (isset($id)) {
        $stmt = $pdo->prepare("SELECT * FROM semestre WHERE id_semestre = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
    } else {
        $stmt = $pdo->query("SELECT * FROM semestre ORDER BY annee_academique, numero_semestre");
        $item = $stmt->fetchAll();
    }
    if (!$item) { http_response_code(404); $item = ['message' => 'Ressource non trouvée']; }
    echo json_encode($item);

} elseif (in_array($method, ['POST', 'PUT', 'DELETE'])) {
    $admin = requireAdmin(); // Seul un admin peut gérer les semestres
    
    if ($method === 'POST') {
        // TODO: Logique de création (INSERT)
        http_response_code(201); echo json_encode(['message' => 'Semestre créé']);
    } elseif ($method === 'PUT' && isset($id)) {
        // TODO: Logique de mise à jour (UPDATE)
        echo json_encode(['message' => 'Semestre mis à jour']);
    } elseif ($method === 'DELETE' && isset($id)) {
        // TODO: Logique de suppression (DELETE)
        echo json_encode(['message' => 'Semestre supprimé']);
    }
}
?>