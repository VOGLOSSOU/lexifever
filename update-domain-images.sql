-- =========================================
-- MISE À JOUR DES URLS D'IMAGES DANS LA TABLE DOMAINS
-- Base de données: u433704782_lexifever_data
-- Date: 20 septembre 2025
-- =========================================

-- Mise à jour des URLs d'images pour chaque domaine
-- Ces URLs proviennent d'Unsplash et sont optimisées pour le web

UPDATE domains SET image_url = 'https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' WHERE name = 'Technologie';

UPDATE domains SET image_url = 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' WHERE name = 'Sciences';

UPDATE domains SET image_url = 'https://images.unsplash.com/photo-1664575599736-c5197c684128?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' WHERE name = 'Business';

UPDATE domains SET image_url = 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' WHERE name = 'Santé';

UPDATE domains SET image_url = 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1471&q=80' WHERE name = 'Arts';

UPDATE domains SET image_url = 'https://images.unsplash.com/photo-1488085061387-422e29b40080?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1631&q=80' WHERE name = 'Voyage';

UPDATE domains SET image_url = 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' WHERE name = 'Sport';

UPDATE domains SET image_url = 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' WHERE name = 'Histoire';

UPDATE domains SET image_url = 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' WHERE name = 'Cuisine';

-- =========================================
-- VÉRIFICATION DE LA MISE À JOUR
-- =========================================

-- Vérifier que toutes les URLs ont été mises à jour
SELECT
    name,
    CASE
        WHEN image_url IS NOT NULL AND image_url != '' THEN '✅ URL présente'
        ELSE '❌ URL manquante'
    END as status,
    LEFT(image_url, 50) as image_preview
FROM domains
ORDER BY name;

-- Compter le nombre de domaines avec URLs d'images
SELECT
    COUNT(*) as total_domains,
    SUM(CASE WHEN image_url IS NOT NULL AND image_url != '' THEN 1 ELSE 0 END) as domains_with_images,
    ROUND((SUM(CASE WHEN image_url IS NOT NULL AND image_url != '' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as percentage_complete
FROM domains;

-- =========================================
-- IMAGES UTILISÉES (RÉFÉRENCE)
-- =========================================
/*
Technologie: Circuit imprimé avec composants électroniques
Sciences: Microscope et équipement de laboratoire
Business: Graphiques et analyses financières
Santé: Cœur et stéthoscope médical
Arts: Peinture et pinceaux d'artiste
Voyage: Avion et carte du monde
Sport: Stade et équipements sportifs
Histoire: Livre ancien et parchemins
Cuisine: Plats cuisinés et ingrédients frais

Toutes les images proviennent d'Unsplash (licence gratuite)
et sont optimisées pour le web (format auto, fit=crop)
*/

-- =========================================
-- FIN DU SCRIPT
-- =========================================