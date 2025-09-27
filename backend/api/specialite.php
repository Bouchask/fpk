<?php
$pdo = getDBConnection();
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM specialite WHERE id_specialite = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
} else {
    $stmt = $pdo->query("SELECT * FROM specialite ORDER BY nom_specialite");
    $item = $stmt->fetchAll();
}
if (!$item) { http_response_code(404); $item = ['message' => 'Ressource non trouvée']; }
echo json_encode($item);
?>