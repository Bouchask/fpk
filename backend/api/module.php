<?php
// backend/api/module.php
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
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM module WHERE id_module = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
    } else {
        // Un admin voit tout, un prof ne voit que ses modules, etc.
        // Logique plus fine à ajouter ici si nécessaire.
        $stmt = $pdo->query("SELECT * FROM module ORDER BY nom_module");
        echo json_encode($stmt->fetchAll());
    }
} elseif (in_array($method, ['POST', 'PUT', 'DELETE'])) {
    // Seul un admin peut créer, modifier ou supprimer un module
    $admin = requireAdmin();

    if ($method === 'POST') {
        // Logique pour créer un module...
        http_response_code(201);
        echo json_encode(['message' => 'Module créé.']);
    } elseif ($method === 'PUT' && isset($id)) {
        // Logique pour modifier un module...
        echo json_encode(['message' => 'Module mis à jour.']);
    } elseif ($method === 'DELETE' && isset($id)) {
        // Logique pour supprimer un module...
        echo json_encode(['message' => 'Module supprimé.']);
    }
}
?>