<?php
$pdo = getDBConnection();
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM semestre WHERE id_semestre = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
} else {
    $stmt = $pdo->query("SELECT * FROM semestre ORDER BY annee_academique, numero_semestre");
    $item = $stmt->fetchAll();
}
if (!$item) { http_response_code(404); $item = ['message' => 'Ressource non trouvée']; }
echo json_encode($item);
?>