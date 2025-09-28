<?php
// backend/config.php

/**
 * Fichier de configuration de la base de données.
 * Contient une unique fonction pour établir et retourner une connexion PDO.
 */
function getDBConnection() {
    // Définition des constantes de connexion
    define('DB_HOST', 'aws-1-ca-central-1.pooler.supabase.com');
    define('DB_PORT', '6543');
    define('DB_NAME', 'postgres');
    define('DB_USER', 'postgres.vzkhxspcvhjtmjyywlya');
    define('DB_PASS', 'Yahya2004@');

    try {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        // Création et retour de l'instance PDO
        return new PDO($dsn, DB_USER, DB_PASS, $options);

    } catch (PDOException $e) {
        // En cas d'échec, on arrête l'exécution de l'API avec une erreur propre
        http_response_code(500);
        echo json_encode(['error' => 'Erreur de connexion a la base de donnees.']);
        exit();
    }
}
?>