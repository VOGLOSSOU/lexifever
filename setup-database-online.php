<?php
/**
 * Script de configuration de la base de données Lexifever (en ligne)
 * Ce script va :
 * 1. Tester la connexion à la base de données en ligne
 * 2. Créer les tables si elles n'existent pas
 * 3. Insérer les données par défaut
 * 4. Vérifier que tout fonctionne
 */

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure la configuration
$config = require __DIR__ . '/src/Config/config.php';

echo "🚀 Configuration de la base de données Lexifever (en ligne)\n";
echo "==================================================\n\n";

// Fonction pour tester la connexion
function testConnection($config) {
    try {
        $dsn = "mysql:host={$config['database']['host']};charset={$config['database']['charset']}";
        $pdo = new PDO($dsn, $config['database']['user'], $config['database']['pass'], $config['database']['options']);

        echo "✅ Connexion au serveur MySQL réussie\n";
        echo "   Host: {$config['database']['host']}\n";
        echo "   User: {$config['database']['user']}\n";

        // Tester la base de données spécifique
        $pdo->exec("USE `{$config['database']['name']}`");
        echo "✅ Base de données '{$config['database']['name']}' accessible\n\n";

        return $pdo;
    } catch (PDOException $e) {
        echo "❌ Erreur de connexion: " . $e->getMessage() . "\n\n";
        return false;
    }
}

// Fonction pour exécuter le schéma SQL
function executeSchema($pdo, $config) {
    try {
        echo "📄 Lecture du fichier schema...\n";
        $schemaFile = __DIR__ . '/database-schema.sql';

        if (!file_exists($schemaFile)) {
            echo "❌ Fichier database-schema.sql non trouvé\n";
            return false;
        }

        $schema = file_get_contents($schemaFile);
        echo "✅ Fichier schema chargé (" . strlen($schema) . " caractères)\n\n";

        // Sélectionner la base de données
        $pdo->exec("USE `{$config['database']['name']}`");

        // Diviser le schéma en requêtes individuelles
        $queries = array_filter(array_map('trim', explode(';', $schema)));

        $successCount = 0;
        $totalQueries = count($queries);

        echo "⚡ Exécution des requêtes SQL...\n";

        foreach ($queries as $index => $query) {
            if (empty($query) || strpos($query, '--') === 0) {
                continue; // Ignorer les commentaires et requêtes vides
            }

            try {
                $pdo->exec($query);
                $successCount++;
                echo "   ✅ Requête " . ($index + 1) . "/{$totalQueries} exécutée\n";
            } catch (PDOException $e) {
                // Vérifier si c'est une erreur acceptable (table déjà existe, etc.)
                if (strpos($e->getMessage(), 'already exists') !== false ||
                    strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    echo "   ⚠️  Requête " . ($index + 1) . "/{$totalQueries} ignorée (élément existe déjà)\n";
                    $successCount++;
                } else {
                    echo "   ❌ Requête " . ($index + 1) . "/{$totalQueries} échouée: " . $e->getMessage() . "\n";
                }
            }
        }

        echo "\n📊 Résultat: {$successCount}/{$totalQueries} requêtes réussies\n\n";
        return $successCount > 0;

    } catch (Exception $e) {
        echo "❌ Erreur lors de l'exécution du schéma: " . $e->getMessage() . "\n\n";
        return false;
    }
}

// Fonction pour vérifier les tables créées
function verifyTables($pdo, $config) {
    try {
        $pdo->exec("USE `{$config['database']['name']}`");

        $tables = ['domains', 'topics', 'generated_texts', 'api_cache', 'api_logs'];
        $createdTables = [];

        echo "🔍 Vérification des tables créées...\n";

        foreach ($tables as $table) {
            $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
            if ($stmt->rowCount() > 0) {
                $createdTables[] = $table;
                echo "   ✅ Table '{$table}' créée\n";
            } else {
                echo "   ❌ Table '{$table}' manquante\n";
            }
        }

        // Vérifier le contenu des tables principales
        if (in_array('domains', $createdTables)) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM domains");
            $count = $stmt->fetch()['count'];
            echo "   📊 {$count} domaines dans la table 'domains'\n";
        }

        if (in_array('topics', $createdTables)) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM topics");
            $count = $stmt->fetch()['count'];
            echo "   📊 {$count} sujets dans la table 'topics'\n";
        }

        echo "\n✅ Vérification terminée\n\n";
        return count($createdTables) === count($tables);

    } catch (Exception $e) {
        echo "❌ Erreur lors de la vérification: " . $e->getMessage() . "\n\n";
        return false;
    }
}

// Fonction pour tester l'API
function testAPI($config) {
    echo "🌐 Test de l'API Lexifever...\n";

    // Test de l'endpoint health
    $healthUrl = "http://localhost/lexifever/api/health";

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Content-Type: application/json',
            'timeout' => 10
        ]
    ]);

    try {
        $response = file_get_contents($healthUrl, false, $context);

        if ($response) {
            $data = json_decode($response, true);
            if ($data && isset($data['success']) && $data['success']) {
                echo "   ✅ API fonctionnelle\n";
                echo "   📊 Status: {$data['message']}\n";
                echo "   🔗 Base de données: " . ($data['data']['services']['database'] === 'OK' ? '✅' : '❌') . "\n";
                echo "   🤖 Gemini API: " . ($data['data']['services']['gemini_api'] === 'OK' ? '✅' : '❌') . "\n";
                return true;
            } else {
                echo "   ❌ Réponse API invalide\n";
                return false;
            }
        } else {
            echo "   ❌ Aucune réponse de l'API\n";
            return false;
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur API: " . $e->getMessage() . "\n";
        return false;
    }
}

// Exécution principale
echo "1️⃣ Test de connexion à la base de données...\n";
$pdo = testConnection($config);

if (!$pdo) {
    echo "❌ Impossible de continuer sans connexion à la base de données\n";
    exit(1);
}

echo "2️⃣ Exécution du schéma de base de données...\n";
$schemaSuccess = executeSchema($pdo, $config);

if (!$schemaSuccess) {
    echo "❌ Échec de l'exécution du schéma\n";
    exit(1);
}

echo "3️⃣ Vérification des tables créées...\n";
$tablesSuccess = verifyTables($pdo, $config);

echo "4️⃣ Test de l'API...\n";
$apiSuccess = testAPI($config);

// Résumé final
echo "==================================================\n";
echo "📋 RÉSUMÉ DE LA CONFIGURATION\n";
echo "==================================================\n";
echo "Base de données: " . ($pdo ? "✅ Connectée" : "❌ Échec") . "\n";
echo "Schémas SQL: " . ($schemaSuccess ? "✅ Exécutés" : "❌ Échec") . "\n";
echo "Tables: " . ($tablesSuccess ? "✅ Créées" : "❌ Manquantes") . "\n";
echo "API: " . ($apiSuccess ? "✅ Fonctionnelle" : "❌ Hors service") . "\n";

if ($pdo && $schemaSuccess && $tablesSuccess && $apiSuccess) {
    echo "\n🎉 CONFIGURATION RÉUSSIE !\n";
    echo "Votre base de données Lexifever est maintenant opérationnelle.\n";
    echo "Vous pouvez commencer à utiliser l'application.\n";
} else {
    echo "\n⚠️ CONFIGURATION PARTIELLE\n";
    echo "Certains éléments n'ont pas pu être configurés correctement.\n";
    echo "Vérifiez les messages d'erreur ci-dessus.\n";
}

echo "\n==================================================\n";
echo "Configuration terminée à " . date('H:i:s') . "\n";
?>