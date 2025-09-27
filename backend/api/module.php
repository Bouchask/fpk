<?php
$pdo = getDBConnection();
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM module WHERE id_module = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
} else {
    $stmt = $pdo->query("SELECT * FROM module ORDER BY nom_module");
    $item = $stmt->fetchAll();
}
if (!$item) { http_response_code(404); $item = ['message' => 'Ressource non trouvée']; }
echo json_encode($item);
?>