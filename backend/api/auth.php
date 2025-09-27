<?php
// backend/api/auth.php

require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Clé secrète. Gardez-la en sécurité et ne la partagez jamais.
define('JWT_SECRET', 'yahya bien');

function createToken($admin) {
    $payload = [
        'iss' => "http://localhost:8000", // Émetteur du token
        'aud' => "http://localhost:8000", // Audience du token
        'iat' => time(), // Heure d'émission
        'exp' => time() + (60 * 60 * 8), // Expiration (ici, 8 heures)
        'data' => [
            'id_admin' => $admin['id_admin'],
            'role' => $admin['role']
        ]
    ];
    return JWT::encode($payload, JWT_SECRET, 'HS256');
}

function verifyAdminToken() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Token d\'accès manquant.']);
        exit();
    }

    try {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        return $decoded->data;
    } catch (Exception $e) {
        http_response_code(403); // Forbidden
        echo json_encode(['message' => 'Accès refusé. Token invalide.']);
        exit();
    }
}
?>