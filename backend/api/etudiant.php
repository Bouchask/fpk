<?php
// backend/api/etudiant.php
require_once 'auth.php';
$pdo = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    verifyAdminToken(); // Seul un admin peut créer un étudiant
    $data = json_decode(file_get_contents('php://input'));

    // TODO: Valider les données (nom, prenom, email, etc.)
    
    $sql = "INSERT INTO etudiant (cne, nom, prenom, email, id_filiere) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$data->cne, $data->nom, $data->prenom, $data->email, $data->id_filiere])) {
        http_response_code(201);
        echo json_encode(['message' => 'Étudiant créé avec succès.']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Erreur lors de la création.']);
    }

} elseif ($method === 'GET') {
    // ... (code GET existant)
}
?>