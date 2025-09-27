<?php
$pdo = getDBConnection();
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM note WHERE id_note = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
} else {
    $stmt = $pdo->query("SELECT * FROM note");
    $item = $stmt->fetchAll();
}
if (!$item) { http_response_code(404); $item = ['message' => 'Ressource non trouvée']; }
echo json_encode($item);
?>