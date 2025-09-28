<?php
// backend/api/index.php

// Charger l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
// CORRECTION ICI: Utilisation de __DIR__ pour un chemin absolu et fiable
require_once __DIR__ . '/../config.php';

$path = trim($_GET['path'] ?? '', '/');
$parts = explode('/', $path);

$resource = $parts[0] ?? null;
$id = $parts[1] ?? null;
$relation = $parts[2] ?? null;

switch ($resource) {
    case 'login': // NOUVELLE ROUTE DE CONNEXION
        require __DIR__ . '/login.php';
        break;
        
    case 'demande':
    case 'etudiant':
    case 'professeur':
    case 'module':
    case 'filiere':
    case 'semestre':
    case 'inscription':
    case 'note':
    case 'specialite':
        require __DIR__ . "/$resource.php";
        break;

    default:
        http_response_code(404);
        echo json_encode(['message' => 'Ressource non trouvée']);
        break;
}
?>