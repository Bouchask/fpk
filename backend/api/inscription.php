<?php
// backend/api/inscription.php
require_once 'auth.php';
$pdo = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];

// Seul un admin peut voir les inscriptions
$admin = requireAdmin();

if ($method === 'GET') {
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM inscription WHERE id_inscription = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
    } else {
        $stmt = $pdo->query("SELECT * FROM inscription ORDER BY date_inscription DESC");
        echo json_encode($stmt->fetchAll());
    }
}
?>