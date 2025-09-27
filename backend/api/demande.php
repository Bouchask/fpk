<?php
// backend/api/index.php

// Charger l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");
require_once '../config.php';

$path = trim($_GET['path'] ?? '', '/');
$parts = explode('/', $path);

$resource = $parts[0] ?? null;
$id = $parts[1] ?? null;
$relation = $parts[2] ?? null;

switch ($resource) {
    case 'admin':
        require 'admin.php';
        break;
        
    case 'demande':
    case 'etudiant':
    case 'professeur':
    case 'module':
        // Pour les autres ressources, on inclut le fichier
        require "$resource.php";
        break;

    // ... (autres cas pour filiere, semestre, etc.)
    case 'filiere':
    case 'semestre':
    case 'inscription':
    case 'note':
    case 'specialite':
        require "$resource.php";
        break;

    default:
        http_response_code(404);
        echo json_encode(['message' => 'Ressource non trouvée']);
        break;
}
?>