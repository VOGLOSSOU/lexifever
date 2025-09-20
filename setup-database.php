<?php
/**
 * Script de configuration de la base de données pour les tests locaux
 * Crée automatiquement toutes les tables nécessaires
 */

// Charger la configuration locale
if (file_exists(__DIR__ . '/src/Config/config.local.php')) {
    $config = require __DIR__ . '/src/Config/config.local.php';
    echo "🔧 Utilisation de la configuration locale\n";
} else {
    $config = require __DIR__ . '/src/Config/config.php';
    echo "🔧 Utilisation de la configuration de production\n";
}

// Inclure les classes nécessaires
require_once __DIR__ . '/src/Utils/Database.php';

echo "🚀 Initialisation de la base de données Lexifever...\n\n";

try {
    // Créer l'instance de base de données
    $db = Database::getInstance();
    echo "✅ Connexion à la base de données établie\n";

    // Créer les tables
    echo "📋 Création des tables...\n";
    $db->createTablesIfNotExist();
    echo "✅ Tables créées avec succès\n";

    // Initialiser les données par défaut
    echo "📊 Initialisation des données par défaut...\n";
    require_once __DIR__ . '/src/Models/Domain.php';
    $domainModel = new Domain();
    $domainModel->initializeDefaultData();
    echo "✅ Données par défaut initialisées\n";

    echo "\n🎉 Configuration terminée avec succès !\n";
    echo "📍 Base de données : " . $config['database']['name'] . "\n";
    echo "🌐 Vous pouvez maintenant accéder à : http://localhost/lexifever/\n";

} catch (Exception $e) {
    echo "❌ Erreur lors de la configuration : " . $e->getMessage() . "\n";
    exit(1);
}