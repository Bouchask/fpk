<?php
// backend/api/auth.php (Mis à jour)
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

define('JWT_SECRET', 'VOTRE_VRAIE_CLE_SECRETE_BEAUCOUP_PLUS_LONGUE');

function createToken(array $user, string $userType) {
    $payload = [
        'iss' => "http://localhost:8000",
        'iat' => time(),
        'exp' => time() + (60 * 60 * 8), // 8 heures
        'data' => [
            'id' => $user['id_' . $userType],
            'role' => $user['role'] ?? $userType,
            'type' => $userType
        ]
    ];
    return JWT::encode($payload, JWT_SECRET, 'HS256');
}

function getAuthenticatedUser() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) return null;
    try {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        return JWT::decode($token, new Key(JWT_SECRET, 'HS256'))->data;
    } catch (Exception $e) {
        return null;
    }
}

function requireAdmin() {
    $user = getAuthenticatedUser();
    if (!$user || ($user->role !== 'superadmin' && $user->role !== 'gestionnaire')) {
        http_response_code(403);
        echo json_encode(['message' => 'Accès refusé. Droits administrateur requis.']);
        exit();
    }
    return $user;
}
?>