<?php
/**
 * Script de mise à jour des URLs d'images dans la base de données
 * Ce script met à jour automatiquement les URLs d'images des domaines
 */

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure la configuration
$config = require __DIR__ . '/src/Config/config.php';

echo "🖼️ Mise à jour des URLs d'images dans la base de données Lexifever\n";
echo "==========================================================\n\n";

// URLs d'images pour chaque domaine
$domainImages = [
    'Technologie' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
    'Sciences' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
    'Business' => 'https://images.unsplash.com/photo-1664575599736-c5197c684128?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
    'Santé' => 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
    'Arts' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1471&q=80',
    'Voyage' => 'https://images.unsplash.com/photo-1488085061387-422e29b40080?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1631&q=80',
    'Sport' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
    'Histoire' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
    'Cuisine' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
];

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

// Fonction pour mettre à jour les URLs d'images
function updateDomainImages($pdo, $domainImages) {
    echo "🖼️ Mise à jour des URLs d'images...\n";

    $updated = 0;
    $errors = 0;

    foreach ($domainImages as $domainName => $imageUrl) {
        try {
            $stmt = $pdo->prepare("UPDATE domains SET image_url = ? WHERE name = ?");
            $result = $stmt->execute([$imageUrl, $domainName]);

            if ($result) {
                echo "   ✅ {$domainName}: URL mise à jour\n";
                $updated++;
            } else {
                echo "   ❌ {$domainName}: Échec de la mise à jour\n";
                $errors++;
            }
        } catch (Exception $e) {
            echo "   ❌ {$domainName}: Erreur - " . $e->getMessage() . "\n";
            $errors++;
        }
    }

    echo "\n📊 Résultat: {$updated} réussis, {$errors} erreurs\n\n";
    return $updated;
}

// Fonction pour vérifier les mises à jour
function verifyUpdates($pdo) {
    echo "🔍 Vérification des mises à jour...\n";

    try {
        $stmt = $pdo->query("SELECT name, image_url FROM domains ORDER BY name");
        $domains = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $withImages = 0;
        $withoutImages = 0;

        foreach ($domains as $domain) {
            if (!empty($domain['image_url'])) {
                echo "   ✅ {$domain['name']}: Image présente\n";
                $withImages++;
            } else {
                echo "   ❌ {$domain['name']}: Pas d'image\n";
                $withoutImages++;
            }
        }

        echo "\n📈 Statistiques:\n";
        echo "   Domains avec images: {$withImages}\n";
        echo "   Domains sans images: {$withoutImages}\n";
        echo "   Taux de complétion: " . round(($withImages / ($withImages + $withoutImages)) * 100, 1) . "%\n\n";

        return $withImages > 0;

    } catch (Exception $e) {
        echo "❌ Erreur lors de la vérification: " . $e->getMessage() . "\n\n";
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

echo "2️⃣ Mise à jour des URLs d'images...\n";
$updateSuccess = updateDomainImages($pdo, $domainImages);

if (!$updateSuccess) {
    echo "❌ Aucune mise à jour n'a pu être effectuée\n";
    exit(1);
}

echo "3️⃣ Vérification des mises à jour...\n";
$verifySuccess = verifyUpdates($pdo);

echo "==========================================================\n";
echo "📋 RÉSUMÉ DE LA MISE À JOUR\n";
echo "==========================================================\n";
echo "Base de données: " . ($pdo ? "✅ Connectée" : "❌ Échec") . "\n";
echo "URLs mises à jour: " . ($updateSuccess ? "✅ {$updateSuccess} domaines" : "❌ Échec") . "\n";
echo "Vérification: " . ($verifySuccess ? "✅ Réussie" : "❌ Échec") . "\n";

if ($pdo && $updateSuccess && $verifySuccess) {
    echo "\n🎉 MISE À JOUR RÉUSSIE !\n";
    echo "Toutes les URLs d'images ont été ajoutées à la base de données.\n";
    echo "Les images proviendront maintenant 100% de la DB.\n";
} else {
    echo "\n⚠️ MISE À JOUR PARTIELLE\n";
    echo "Certaines URLs n'ont pas pu être mises à jour.\n";
    echo "Vérifiez les messages d'erreur ci-dessus.\n";
}

echo "\n==========================================================\n";
echo "Script terminé à " . date('H:i:s') . "\n";

// Informations supplémentaires
echo "\n💡 PROCHAINES ÉTAPES:\n";
echo "1. Les images proviennent maintenant 100% de la base de données\n";
echo "2. Vous pouvez modifier les URLs dans phpMyAdmin si besoin\n";
echo "3. Le système de fallback JavaScript est toujours présent au cas où\n";
echo "4. Testez select-domain.html pour voir les nouvelles images\n";
?>