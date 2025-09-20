<?php
/**
 * Script d'ajout des URLs d'images aux sujets
 * Ce script ajoute la colonne image_url et met à jour tous les sujets
 */

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure la configuration
$config = require __DIR__ . '/src/Config/config.php';

echo "🖼️ Ajout des URLs d'images aux sujets Lexifever\n";
echo "===============================================\n\n";

// URLs d'images pour chaque sujet (même format qu'Unsplash)
$topicImages = [
    // TECHNOLOGIE
    'Intelligence Artificielle' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&h=300&fit=crop&auto=format',
    'Développement Web' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&h=300&fit=crop&auto=format',
    'Cybersécurité' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=400&h=300&fit=crop&auto=format',
    'Blockchain' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=400&h=300&fit=crop&auto=format',
    'Développement Mobile' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=300&fit=crop&auto=format',
    'Cloud Computing' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=400&h=300&fit=crop&auto=format',

    // SCIENCES
    'Astronomie' => 'https://images.unsplash.com/photo-1446776653964-20c1d3a81b06?w=400&h=300&fit=crop&auto=format',
    'Biologie' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=400&h=300&fit=crop&auto=format',
    'Physique' => 'https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?w=400&h=300&fit=crop&auto=format',
    'Chimie' => 'https://images.unsplash.com/photo-1603126857599-f6e157fa2fe6?w=400&h=300&fit=crop&auto=format',

    // BUSINESS
    'Marketing Digital' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=300&fit=crop&auto=format',
    'Entrepreneuriat' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=400&h=300&fit=crop&auto=format',
    'Finance' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&h=300&fit=crop&auto=format',
    'Management' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=300&fit=crop&auto=format',

    // SANTÉ
    'Nutrition' => 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71?w=400&h=300&fit=crop&auto=format',
    'Fitness' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300&fit=crop&auto=format',
    'Médecine' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=300&fit=crop&auto=format',
    'Bien-être Mental' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=400&h=300&fit=crop&auto=format',

    // ARTS
    'Peinture' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=400&h=300&fit=crop&auto=format',
    'Musique' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400&h=300&fit=crop&auto=format',
    'Littérature' => 'https://images.unsplash.com/photo-1489599735734-79b4dfe3b22a?w=400&h=300&fit=crop&auto=format',
    'Cinéma' => 'https://images.unsplash.com/photo-1489599735734-79b4dfe3b22a?w=400&h=300&fit=crop&auto=format',

    // VOYAGE
    'Destinations Exotiques' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=400&h=300&fit=crop&auto=format',
    'Aventure et Nature' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop&auto=format',
    'Voyage Urbain' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400&h=300&fit=crop&auto=format',
    'Conseils de Voyage' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=400&h=300&fit=crop&auto=format',

    // SPORT
    'Football' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&h=300&fit=crop&auto=format',
    'Basketball' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=400&h=300&fit=crop&auto=format',
    'Tennis' => 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=300&fit=crop&auto=format',
    'Sports Olympiques' => 'https://images.unsplash.com/photo-1464207687429-7505649dae38?w=400&h=300&fit=crop&auto=format',

    // HISTOIRE
    'Histoire Ancienne' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=300&fit=crop&auto=format',
    'Histoire Moderne' => 'https://images.unsplash.com/photo-1589652717521-10c0d092dea9?w=400&h=300&fit=crop&auto=format',
    'Histoire Contemporaine' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=400&h=300&fit=crop&auto=format',
    'Biographies' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=300&fit=crop&auto=format',

    // CUISINE
    'Cuisine Française' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop&auto=format',
    'Cuisine Internationale' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop&auto=format',
    'Pâtisserie' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=300&fit=crop&auto=format',
    'Cuisine Saine' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400&h=300&fit=crop&auto=format'
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

// Fonction pour ajouter la colonne image_url
function addImageColumn($pdo) {
    try {
        echo "📄 Vérification de la colonne image_url...\n";

        // Vérifier si la colonne existe déjà
        $result = $pdo->query("SHOW COLUMNS FROM topics LIKE 'image_url'");
        $exists = $result->fetch();

        if ($exists) {
            echo "✅ La colonne image_url existe déjà\n\n";
            return true;
        }

        // Ajouter la colonne
        $pdo->exec("ALTER TABLE topics ADD COLUMN image_url VARCHAR(500) DEFAULT NULL AFTER description");
        echo "✅ Colonne image_url ajoutée\n";

        // Créer l'index
        $pdo->exec("CREATE INDEX idx_topics_image_url ON topics(image_url)");
        echo "✅ Index créé pour les performances\n\n";

        return true;

    } catch (Exception $e) {
        echo "❌ Erreur lors de l'ajout de la colonne: " . $e->getMessage() . "\n\n";
        return false;
    }
}

// Fonction pour mettre à jour les URLs d'images
function updateTopicImages($pdo, $topicImages) {
    echo "🖼️ Mise à jour des URLs d'images des sujets...\n";

    $updated = 0;
    $errors = 0;

    foreach ($topicImages as $topicName => $imageUrl) {
        try {
            // Trouver le domaine du sujet pour faire la liaison
            $stmt = $pdo->prepare("
                SELECT t.id, d.name as domain_name
                FROM topics t
                JOIN domains d ON t.domain_id = d.id
                WHERE t.name = ? AND t.is_active = 1
            ");
            $stmt->execute([$topicName]);
            $topic = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($topic) {
                // Mettre à jour l'URL de l'image
                $updateStmt = $pdo->prepare("UPDATE topics SET image_url = ? WHERE id = ?");
                $result = $updateStmt->execute([$imageUrl, $topic['id']]);

                if ($result) {
                    echo "   ✅ {$topicName} ({$topic['domain_name']}): URL mise à jour\n";
                    $updated++;
                } else {
                    echo "   ❌ {$topicName}: Échec de la mise à jour\n";
                    $errors++;
                }
            } else {
                echo "   ⚠️ {$topicName}: Sujet non trouvé dans la DB\n";
                $errors++;
            }

        } catch (Exception $e) {
            echo "   ❌ {$topicName}: Erreur - " . $e->getMessage() . "\n";
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
        $stmt = $pdo->query("
            SELECT
                d.name as domain_name,
                t.name as topic_name,
                CASE
                    WHEN t.image_url IS NOT NULL AND t.image_url != '' THEN '✅ URL présente'
                    ELSE '❌ URL manquante'
                END as status
            FROM topics t
            JOIN domains d ON t.domain_id = d.id
            WHERE t.is_active = 1
            ORDER BY d.name, t.name
        ");

        $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $withImages = 0;
        $total = count($topics);

        foreach ($topics as $topic) {
            echo "   {$topic['status']} {$topic['topic_name']} ({$topic['domain_name']})\n";
            if (strpos($topic['status'], '✅') !== false) {
                $withImages++;
            }
        }

        echo "\n📈 Statistiques:\n";
        echo "   Sujets totaux: {$total}\n";
        echo "   Sujets avec images: {$withImages}\n";
        echo "   Taux de complétion: " . round(($withImages / $total) * 100, 1) . "%\n\n";

        return $withImages > 0;

    } catch (Exception $e) {
        echo "❌ Erreur lors de la vérification: " . $e->getMessage() . "\n\n";
        return false;
    }
}

// Fonction pour afficher les statistiques par domaine
function showDomainStats($pdo) {
    echo "📊 Statistiques par domaine:\n";

    try {
        $stmt = $pdo->query("
            SELECT
                d.name as domain_name,
                COUNT(t.id) as total_topics,
                SUM(CASE WHEN t.image_url IS NOT NULL AND t.image_url != '' THEN 1 ELSE 0 END) as topics_with_images,
                ROUND((SUM(CASE WHEN t.image_url IS NOT NULL AND t.image_url != '' THEN 1 ELSE 0 END) / COUNT(t.id)) * 100, 1) as completion_percentage
            FROM domains d
            LEFT JOIN topics t ON d.id = t.domain_id AND t.is_active = 1
            WHERE d.is_active = 1
            GROUP BY d.id, d.name
            ORDER BY d.name
        ");

        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($stats as $stat) {
            $percentage = $stat['completion_percentage'];
            $status = $percentage == 100.0 ? '✅' : ($percentage > 0 ? '🔶' : '❌');
            echo "   {$status} {$stat['domain_name']}: {$stat['topics_with_images']}/{$stat['total_topics']} ({$percentage}%)\n";
        }

        echo "\n";

    } catch (Exception $e) {
        echo "❌ Erreur lors des statistiques: " . $e->getMessage() . "\n\n";
    }
}

// Exécution principale
echo "1️⃣ Test de connexion à la base de données...\n";
$pdo = testConnection($config);

if (!$pdo) {
    echo "❌ Impossible de continuer sans connexion à la base de données\n";
    exit(1);
}

echo "2️⃣ Ajout de la colonne image_url...\n";
$columnAdded = addImageColumn($pdo);

if (!$columnAdded) {
    echo "❌ Impossible d'ajouter la colonne image_url\n";
    exit(1);
}

echo "3️⃣ Mise à jour des URLs d'images...\n";
$updateSuccess = updateTopicImages($pdo, $topicImages);

echo "4️⃣ Vérification des mises à jour...\n";
$verifySuccess = verifyUpdates($pdo);

echo "5️⃣ Statistiques par domaine...\n";
showDomainStats($pdo);

echo "===============================================\n";
echo "📋 RÉSUMÉ DE LA MISE À JOUR\n";
echo "===============================================\n";
echo "Base de données: " . ($pdo ? "✅ Connectée" : "❌ Échec") . "\n";
echo "Colonne ajoutée: " . ($columnAdded ? "✅ Réussie" : "❌ Échec") . "\n";
echo "URLs mises à jour: " . ($updateSuccess ? "✅ {$updateSuccess} sujets" : "❌ Échec") . "\n";
echo "Vérification: " . ($verifySuccess ? "✅ Réussie" : "❌ Échec") . "\n";

if ($pdo && $columnAdded && $updateSuccess && $verifySuccess) {
    echo "\n🎉 MISE À JOUR RÉUSSIE !\n";
    echo "Toutes les URLs d'images ont été ajoutées aux sujets.\n";
    echo "Les sujets peuvent maintenant afficher leurs images depuis la DB.\n";
} else {
    echo "\n⚠️ MISE À JOUR PARTIELLE\n";
    echo "Certaines URLs n'ont pas pu être mises à jour.\n";
    echo "Vérifiez les messages d'erreur ci-dessus.\n";
}

echo "\n===============================================\n";
echo "Script terminé à " . date('H:i:s') . "\n";

// Informations supplémentaires
echo "\n💡 PROCHAINES ÉTAPES:\n";
echo "1. Les images des sujets proviennent maintenant de la DB\n";
echo "2. Modifier select-topic.html pour afficher les images\n";
echo "3. Tester l'affichage des sujets avec leurs images\n";
echo "4. Vérifier que tout fonctionne correctement\n";
?>