<?php
/**
 * Script de test pour l'API Lexifever
 */

require_once 'src/Config/config.php';
require_once 'src/Utils/ApiResponse.php';
require_once 'src/Utils/Database.php';
require_once 'src/Utils/Validator.php';
require_once 'src/Utils/Cache.php';
require_once 'src/Services/GeminiAIService.php';

header('Content-Type: application/json');

try {
    echo "=== TEST API LEXIFEVER ===\n\n";

    // Test 1: Configuration
    echo "1. Test de configuration:\n";
    $config = require 'src/Config/config.php';
    echo "- Clé API configurée: " . (!empty($config['gemini']['api_key']) ? 'OUI' : 'NON') . "\n";
    echo "- URL de base: " . $config['gemini']['base_url'] . "\n\n";

    // Test 2: Service Gemini
    echo "2. Test du service Gemini:\n";
    $gemini = new GeminiAIService();
    $health = $gemini->healthCheck();
    echo "- Santé API: " . $health['status'] . "\n";
    echo "- Clé API configurée: " . ($health['apiKeyConfigured'] ? 'OUI' : 'NON') . "\n";
    if ($health['status'] !== 'OK') {
        echo "- Message d'erreur: " . $health['message'] . "\n";
    }
    echo "\n";

    // Test 3: Génération de texte simple
    echo "3. Test de génération de texte:\n";
    $testParams = [
        'domain' => 'Technologie',
        'topic' => 'Intelligence Artificielle',
        'level' => 'beginner',
        'tone' => 'informative',
        'length' => 'short'
    ];

    echo "- Paramètres de test: " . json_encode($testParams) . "\n";

    $result = $gemini->generateText($testParams);
    echo "- Génération réussie: OUI\n";
    echo "- Longueur du texte: " . strlen($result['englishText']) . " caractères\n";
    echo "- Modèle utilisé: " . $result['model'] . "\n";
    echo "- Début du texte: " . substr($result['englishText'], 0, 100) . "...\n";

} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . " (ligne " . $e->getLine() . ")\n";
}

echo "\n=== FIN DU TEST ===";
?>