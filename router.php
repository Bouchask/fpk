<?php
// FPK/router.php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 1. Si la requête est pour l'API, on la redirige vers le routeur de l'API
if (strpos($path, '/backend/api/') === 0) {
    // On recrée la variable $_GET['path'] que notre .htaccess aurait générée
    $_GET['path'] = substr($path, strlen('/backend/api/'));
    require __DIR__ . '/backend/api/index.php';
    return;
}

// 2. Si le fichier demandé existe (comme un .js, .css, .html), on le sert directement
if (file_exists(__DIR__ . $path)) {
    return false; // Laisse le serveur PHP intégré gérer le fichier.
}

// 3. Si rien ne correspond, on affiche une erreur 404
http_response_code(404);
echo "Erreur 404: La ressource '$path' n'a pas été trouvée.";