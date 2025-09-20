<?php
/**
 * Script de test simple pour diagnostiquer les problèmes API
 */

echo "🔍 Test de l'API Lexifever\n\n";

// Test 1: Vérifier la configuration
echo "1. Test de la configuration...\n";
try {
    if (file_exists(__DIR__ . '/src/Config/config.local.php')) {
        $config = require __DIR__ . '/src/Config/config.local.php';
        echo "✅ Configuration locale chargée\n";
    } else {
        $config = require __DIR__ . '/src/Config/config.php';
        echo "✅ Configuration de production chargée\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur de configuration: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Vérifier les inclusions
echo "\n2. Test des inclusions...\n";
$files = [
    'src/Utils/ApiResponse.php',
    'src/Controllers/ApiController.php'
];

foreach ($files as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ {$file} existe\n";
        try {
            require_once __DIR__ . '/' . $file;
            echo "✅ {$file} inclus avec succès\n";
        } catch (Exception $e) {
            echo "❌ Erreur lors de l'inclusion de {$file}: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ {$file} n'existe pas\n";
    }
}

// Test 3: Test de l'API Health
echo "\n3. Test de l'endpoint /api/health...\n";
try {
    $apiController = new ApiController();
    echo "✅ ApiController instancié\n";

    // Simuler un appel à health
    ob_start();
    $apiController->health();
    $output = ob_get_clean();

    echo "✅ Endpoint health appelé\n";
    echo "📄 Sortie: " . substr($output, 0, 200) . "...\n";

} catch (Exception $e) {
    echo "❌ Erreur lors du test de l'API: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "\n";
}

echo "\n🏁 Test terminé\n";