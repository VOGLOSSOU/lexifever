-- =========================================
-- AJOUT DE LA COLONNE IMAGE_URL À LA TABLE TOPICS
-- Base de données: u433704782_lexifever_data
-- Date: 20 septembre 2025
-- =========================================

-- Ajouter la colonne image_url à la table topics
ALTER TABLE topics ADD COLUMN image_url VARCHAR(500) DEFAULT NULL AFTER description;

-- Créer un index pour optimiser les performances
CREATE INDEX idx_topics_image_url ON topics(image_url);

-- =========================================
-- MISE À JOUR DES URLS D'IMAGES POUR LES SUJETS
-- Utilisation d'Unsplash comme source (même que pour les domaines)
-- =========================================

-- TECHNOLOGIE (6 sujets)
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=400&h=300&fit=crop&auto=format' WHERE name = 'Intelligence Artificielle' AND domain_id = (SELECT id FROM domains WHERE name = 'Technologie');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&h=300&fit=crop&auto=format' WHERE name = 'Développement Web' AND domain_id = (SELECT id FROM domains WHERE name = 'Technologie');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=400&h=300&fit=crop&auto=format' WHERE name = 'Cybersécurité' AND domain_id = (SELECT id FROM domains WHERE name = 'Technologie');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=400&h=300&fit=crop&auto=format' WHERE name = 'Blockchain' AND domain_id = (SELECT id FROM domains WHERE name = 'Technologie');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=300&fit=crop&auto=format' WHERE name = 'Développement Mobile' AND domain_id = (SELECT id FROM domains WHERE name = 'Technologie');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=400&h=300&fit=crop&auto=format' WHERE name = 'Cloud Computing' AND domain_id = (SELECT id FROM domains WHERE name = 'Technologie');

-- SCIENCES (4 sujets)
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1446776653964-20c1d3a81b06?w=400&h=300&fit=crop&auto=format' WHERE name = 'Astronomie' AND domain_id = (SELECT id FROM domains WHERE name = 'Sciences');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=400&h=300&fit=crop&auto=format' WHERE name = 'Biologie' AND domain_id = (SELECT id FROM domains WHERE name = 'Sciences');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?w=400&h=300&fit=crop&auto=format' WHERE name = 'Physique' AND domain_id = (SELECT id FROM domains WHERE name = 'Sciences');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1603126857599-f6e157fa2fe6?w=400&h=300&fit=crop&auto=format' WHERE name = 'Chimie' AND domain_id = (SELECT id FROM domains WHERE name = 'Sciences');

-- BUSINESS (4 sujets)
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=300&fit=crop&auto=format' WHERE name = 'Marketing Digital' AND domain_id = (SELECT id FROM domains WHERE name = 'Business');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=400&h=300&fit=crop&auto=format' WHERE name = 'Entrepreneuriat' AND domain_id = (SELECT id FROM domains WHERE name = 'Business');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&h=300&fit=crop&auto=format' WHERE name = 'Finance' AND domain_id = (SELECT id FROM domains WHERE name = 'Business');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=300&fit=crop&auto=format' WHERE name = 'Management' AND domain_id = (SELECT id FROM domains WHERE name = 'Business');

-- SANTÉ (4 sujets)
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71?w=400&h=300&fit=crop&auto=format' WHERE name = 'Nutrition' AND domain_id = (SELECT id FROM domains WHERE name = 'Santé');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300&fit=crop&auto=format' WHERE name = 'Fitness' AND domain_id = (SELECT id FROM domains WHERE name = 'Santé');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=300&fit=crop&auto=format' WHERE name = 'Médecine' AND domain_id = (SELECT id FROM domains WHERE name = 'Santé');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=400&h=300&fit=crop&auto=format' WHERE name = 'Bien-être Mental' AND domain_id = (SELECT id FROM domains WHERE name = 'Santé');

-- ARTS (4 sujets)
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=400&h=300&fit=crop&auto=format' WHERE name = 'Peinture' AND domain_id = (SELECT id FROM domains WHERE name = 'Arts');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400&h=300&fit=crop&auto=format' WHERE name = 'Musique' AND domain_id = (SELECT id FROM domains WHERE name = 'Arts');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1489599735734-79b4dfe3b22a?w=400&h=300&fit=crop&auto=format' WHERE name = 'Littérature' AND domain_id = (SELECT id FROM domains WHERE name = 'Arts');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1489599735734-79b4dfe3b22a?w=400&h=300&fit=crop&auto=format' WHERE name = 'Cinéma' AND domain_id = (SELECT id FROM domains WHERE name = 'Arts');

-- VOYAGE (4 sujets)
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=400&h=300&fit=crop&auto=format' WHERE name = 'Destinations Exotiques' AND domain_id = (SELECT id FROM domains WHERE name = 'Voyage');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop&auto=format' WHERE name = 'Aventure et Nature' AND domain_id = (SELECT id FROM domains WHERE name = 'Voyage');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400&h=300&fit=crop&auto=format' WHERE name = 'Voyage Urbain' AND domain_id = (SELECT id FROM domains WHERE name = 'Voyage');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=400&h=300&fit=crop&auto=format' WHERE name = 'Conseils de Voyage' AND domain_id = (SELECT id FROM domains WHERE name = 'Voyage');

-- SPORT (4 sujets)
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&h=300&fit=crop&auto=format' WHERE name = 'Football' AND domain_id = (SELECT id FROM domains WHERE name = 'Sport');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=400&h=300&fit=crop&auto=format' WHERE name = 'Basketball' AND domain_id = (SELECT id FROM domains WHERE name = 'Sport');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=300&fit=crop&auto=format' WHERE name = 'Tennis' AND domain_id = (SELECT id FROM domains WHERE name = 'Sport');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1464207687429-7505649dae38?w=400&h=300&fit=crop&auto=format' WHERE name = 'Sports Olympiques' AND domain_id = (SELECT id FROM domains WHERE name = 'Sport');

-- HISTOIRE (4 sujets)
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=300&fit=crop&auto=format' WHERE name = 'Histoire Ancienne' AND domain_id = (SELECT id FROM domains WHERE name = 'Histoire');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1589652717521-10c0d092dea9?w=400&h=300&fit=crop&auto=format' WHERE name = 'Histoire Moderne' AND domain_id = (SELECT id FROM domains WHERE name = 'Histoire');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=400&h=300&fit=crop&auto=format' WHERE name = 'Histoire Contemporaine' AND domain_id = (SELECT id FROM domains WHERE name = 'Histoire');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=300&fit=crop&auto=format' WHERE name = 'Biographies' AND domain_id = (SELECT id FROM domains WHERE name = 'Histoire');

-- CUISINE (4 sujets)
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop&auto=format' WHERE name = 'Cuisine Française' AND domain_id = (SELECT id FROM domains WHERE name = 'Cuisine');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop&auto=format' WHERE name = 'Cuisine Internationale' AND domain_id = (SELECT id FROM domains WHERE name = 'Cuisine');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=300&fit=crop&auto=format' WHERE name = 'Pâtisserie' AND domain_id = (SELECT id FROM domains WHERE name = 'Cuisine');
UPDATE topics SET image_url = 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400&h=300&fit=crop&auto=format' WHERE name = 'Cuisine Saine' AND domain_id = (SELECT id FROM domains WHERE name = 'Cuisine');

-- =========================================
-- VÉRIFICATION DE LA MISE À JOUR
-- =========================================

-- Vérifier que toutes les URLs ont été mises à jour
SELECT
    d.name as domain_name,
    t.name as topic_name,
    CASE
        WHEN t.image_url IS NOT NULL AND t.image_url != '' THEN '✅ URL présente'
        ELSE '❌ URL manquante'
    END as status
FROM topics t
JOIN domains d ON t.domain_id = d.id
ORDER BY d.name, t.name;

-- Statistiques des images par domaine
SELECT
    d.name as domain_name,
    COUNT(t.id) as total_topics,
    SUM(CASE WHEN t.image_url IS NOT NULL AND t.image_url != '' THEN 1 ELSE 0 END) as topics_with_images,
    ROUND((SUM(CASE WHEN t.image_url IS NOT NULL AND t.image_url != '' THEN 1 ELSE 0 END) / COUNT(t.id)) * 100, 1) as completion_percentage
FROM domains d
LEFT JOIN topics t ON d.id = t.domain_id AND t.is_active = 1
WHERE d.is_active = 1
GROUP BY d.id, d.name
ORDER BY d.name;

-- Statistiques globales
SELECT
    COUNT(*) as total_topics,
    SUM(CASE WHEN image_url IS NOT NULL AND image_url != '' THEN 1 ELSE 0 END) as topics_with_images,
    ROUND((SUM(CASE WHEN image_url IS NOT NULL AND image_url != '' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as overall_completion
FROM topics
WHERE is_active = 1;

-- =========================================
-- FIN DU SCRIPT
-- =========================================