<?php
$pdo = getDBConnection();
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM inscription WHERE id_inscription = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
} else {
    $stmt = $pdo->query("SELECT * FROM inscription ORDER BY date_inscription DESC");
    $item = $stmt->fetchAll();
}
if (!$item) { http_response_code(404); $item = ['message' => 'Ressource non trouvée']; }
echo json_encode($item);
?>