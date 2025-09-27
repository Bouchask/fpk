<?php
// backend/config.php

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
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur de connexion à la base de données.']);
        exit();
    }
}
?><?php

/**
 * Fichier de configuration et de test de la connexion à la base de données.
 *
 * Ce script effectue les actions suivantes :
 * 1. Définit les informations de connexion de manière sécurisée.
 * 2. Tente de se connecter à la base de données PostgreSQL via PDO.
 * 3. En cas de succès, il affiche un message de confirmation.
 * 4. Il liste ensuite toutes les tables visibles dans le schéma public.
 * 5. En cas d'échec, il affiche un message d'erreur clair.
 */

// 1. Définition des constantes de connexion avec vos informations
define('DB_HOST', 'aws-1-ca-central-1.pooler.supabase.com');
define('DB_PORT', '6543');
define('DB_NAME', 'postgres');
define('DB_USER', 'postgres.vzkhxspcvhjtmjyywlya');
define('DB_PASS', 'Yahya2004@');

// 2. Tentative de connexion dans un bloc try...catch pour la gestion des erreurs
try {
    // Construction du DSN (Data Source Name) pour PostgreSQL
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;

    // Options de connexion PDO pour la performance et la sécurité
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Les erreurs lèvent des exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Résultats en tableau associatif
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Utilisation des vraies requêtes préparées
    ];

    // Création de l'instance PDO
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    // Si on arrive ici, la connexion a réussi
    echo "<h2 style='color: green;'>✅ Connexion à la base de données établie avec succès !</h2>";

    // 3. Requête pour récupérer et afficher la liste de toutes les tables
    echo "<h3>Tables disponibles dans la base de données :</h3>";

    // La requête cible le catalogue système de PostgreSQL pour lister les tables du schéma 'public'
    $sql = "SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public' ORDER BY tablename";
    
    $stmt = $pdo->query($sql);
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            // Utiliser htmlspecialchars pour se protéger contre les attaques XSS
            echo "<li>" . htmlspecialchars($table) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucune table trouvée dans le schéma public.</p>";
    }

} catch (PDOException $e) {
    // 4. En cas d'erreur, on affiche un message sécurisé
    http_response_code(500); // Code d'erreur serveur
    echo "<h2 style='color: red;'>❌ Erreur de connexion à la base de données.</h2>";
    echo "<p>Veuillez vérifier vos informations de connexion ou contacter un administrateur.</p>";
    // Ligne à utiliser seulement en développement pour déboguer :
    // die("Erreur détaillée : " . $e->getMessage());
}

?>