<?php
/**
 * Script de test pour Lexifever PHP
 * Teste la base de données, l'API Gemini et les fonctionnalités principales
 */

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🧪 Tests Lexifever PHP</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} pre{background:#f5f5f5;padding:10px;border-radius:5px;overflow-x:auto;}</style>";

// Inclure les classes nécessaires
require_once __DIR__ . '/src/Config/config.php';
require_once __DIR__ . '/src/Utils/Database.php';
require_once __DIR__ . '/src/Services/GeminiAIService.php';
require_once __DIR__ . '/src/Models/Domain.php';
require_once __DIR__ . '/src/Controllers/ApiController.php';

$tests = [
    'database' => false,
    'tables' => false,
    'gemini_api' => false,
    'domains' => false,
    'api_endpoints' => false
];

$results = [];

// Test 1: Connexion à la base de données
echo "<h2>1. Test de la base de données</h2>";
try {
    $db = Database::getInstance();
    $results[] = "✅ Connexion à la base de données réussie";
    $tests['database'] = true;

    // Test de création des tables
    echo "<h3>Test de création des tables</h3>";
    $db->createTablesIfNotExist();
    $results[] = "✅ Tables créées ou déjà existantes";
    $tests['tables'] = true;

} catch (Exception $e) {
    $results[] = "❌ Erreur base de données: " . $e->getMessage();
    echo "<div class='error'>Erreur: " . $e->getMessage() . "</div>";
}

// Test 2: API Google Gemini
echo "<h2>2. Test de l'API Google Gemini</h2>";
try {
    $gemini = new GeminiAIService();
    $health = $gemini->healthCheck();

    if ($health['status'] === 'OK') {
        $results[] = "✅ API Gemini fonctionnelle";
        $tests['gemini_api'] = true;
    } else {
        $results[] = "⚠️ API Gemini: " . $health['message'];
    }

    echo "<pre>" . print_r($health, true) . "</pre>";

} catch (Exception $e) {
    $results[] = "❌ Erreur API Gemini: " . $e->getMessage();
    echo "<div class='error'>Erreur: " . $e->getMessage() . "</div>";
}

// Test 3: Modèles de données
echo "<h2>3. Test des modèles de données</h2>";
try {
    $domainModel = new Domain();

    // Tester l'initialisation des données
    $initialized = $domainModel->initializeDefaultData();
    if ($initialized) {
        $results[] = "✅ Données par défaut initialisées";
        $tests['domains'] = true;
    }

    // Tester la récupération des domaines
    $domains = $domainModel->getAll();
    $results[] = "✅ " . count($domains) . " domaines récupérés";

    echo "<pre>Domaines disponibles: " . implode(', ', array_column($domains, 'name')) . "</pre>";

} catch (Exception $e) {
    $results[] = "❌ Erreur modèles: " . $e->getMessage();
    echo "<div class='error'>Erreur: " . $e->getMessage() . "</div>";
}

// Test 4: Endpoints API
echo "<h2>4. Test des endpoints API</h2>";
try {
    $apiController = new ApiController();

    // Test de l'endpoint health
    echo "<h3>Test endpoint /api/health</h3>";
    ob_start();
    $apiController->health();
    $output = ob_get_clean();

    if (strpos($output, '"success":true') !== false) {
        $results[] = "✅ Endpoint /api/health fonctionnel";
        $tests['api_endpoints'] = true;
    } else {
        $results[] = "❌ Endpoint /api/health défaillant";
    }

    echo "<pre>" . htmlspecialchars(substr($output, 0, 500)) . "...</pre>";

} catch (Exception $e) {
    $results[] = "❌ Erreur endpoints API: " . $e->getMessage();
    echo "<div class='error'>Erreur: " . $e->getMessage() . "</div>";
}

// Test 5: Génération de test (optionnel)
echo "<h2>5. Test de génération de texte (optionnel)</h2>";
echo "<p>Ce test peut prendre quelques secondes...</p>";

try {
    $gemini = new GeminiAIService();
    $testParams = [
        'domain' => 'Technologie',
        'topic' => 'Intelligence Artificielle',
        'level' => 'intermediate',
        'tone' => 'informative',
        'length' => 'short'
    ];

    $startTime = microtime(true);
    $result = $gemini->generateText($testParams);
    $endTime = microtime(true);

    $responseTime = round(($endTime - $startTime) * 1000, 2);

    if (isset($result['englishText']) && !empty($result['englishText'])) {
        $results[] = "✅ Génération de texte réussie ({$responseTime}ms)";
        echo "<div class='success'>Texte généré avec succès !</div>";
        echo "<p><strong>Temps de réponse:</strong> {$responseTime}ms</p>";
        echo "<p><strong>Aperçu:</strong> " . htmlspecialchars(substr($result['englishText'], 0, 200)) . "...</p>";
    } else {
        $results[] = "❌ Génération de texte échouée";
    }

} catch (Exception $e) {
    $results[] = "❌ Erreur génération: " . $e->getMessage();
    echo "<div class='error'>Erreur: " . $e->getMessage() . "</div>";
}

// Résumé des tests
echo "<h2>📊 Résumé des tests</h2>";
echo "<ul>";
foreach ($results as $result) {
    echo "<li>$result</li>";
}
echo "</ul>";

// État général
$passedTests = array_sum($tests);
$totalTests = count($tests);

echo "<h2>🎯 État général</h2>";
if ($passedTests === $totalTests) {
    echo "<div class='success'>🎉 Tous les tests sont passés ! L'application est prête.</div>";
} elseif ($passedTests >= 3) {
    echo "<div class='warning'>⚠️ La plupart des tests sont passés. Quelques ajustements mineurs peuvent être nécessaires.</div>";
} else {
    echo "<div class='error'>❌ Plusieurs tests ont échoué. Vérifiez la configuration.</div>";
}

echo "<p><strong>Tests réussis:</strong> {$passedTests}/{$totalTests}</p>";

// Informations système
echo "<h2>ℹ️ Informations système</h2>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . PHP_VERSION . "</li>";
echo "<li><strong>Système:</strong> " . PHP_OS . "</li>";
echo "<li><strong>Extensions PDO:</strong> " . (extension_loaded('pdo_mysql') ? '✅' : '❌') . "</li>";
echo "<li><strong>cURL:</strong> " . (extension_loaded('curl') ? '✅' : '❌') . "</li>";
echo "<li><strong>JSON:</strong> " . (extension_loaded('json') ? '✅' : '❌') . "</li>";
echo "</ul>";

// Configuration actuelle
echo "<h2>⚙️ Configuration actuelle</h2>";
$config = require __DIR__ . '/src/Config/config.php';
echo "<pre>";
echo "Base de données: {$config['database']['host']}\n";
echo "API Gemini: " . (empty($config['gemini']['api_key']) ? '❌ Non configurée' : '✅ Configurée') . "\n";
echo "Cache: " . ($config['cache']['enabled'] ? '✅ Activé' : '❌ Désactivé') . "\n";
echo "Debug: " . ($config['app']['debug'] ? '✅ Activé' : '❌ Désactivé') . "\n";
echo "</pre>";

echo "<hr>";
echo "<p><strong>Test terminé à:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><a href='index.html'>🏠 Retour à l'accueil</a></p>";
?>