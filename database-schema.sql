-- =========================================
-- LEXIFEVER DATABASE SCHEMA
-- Base de données: u433704782_lexifever_data
-- Créé le: 20 septembre 2025
-- =========================================

-- =========================================
-- TABLE: domains
-- Description: Stocke les domaines d'intérêt
-- =========================================
CREATE TABLE IF NOT EXISTS domains (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image_url VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLE: topics
-- Description: Stocke les sujets spécifiques par domaine
-- =========================================
CREATE TABLE IF NOT EXISTS topics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    domain_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (domain_id) REFERENCES domains(id) ON DELETE CASCADE,
    UNIQUE KEY unique_domain_topic (domain_id, name),
    INDEX idx_domain (domain_id),
    INDEX idx_name (name),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLE: generated_texts
-- Description: Stocke les textes générés par l'IA
-- =========================================
CREATE TABLE IF NOT EXISTS generated_texts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(255) NOT NULL,
    domain VARCHAR(100) NOT NULL,
    topic VARCHAR(100) NOT NULL,
    level ENUM('beginner', 'intermediate', 'advanced') NOT NULL,
    tone VARCHAR(50) NOT NULL,
    length ENUM('short', 'medium', 'long') NOT NULL,
    include_examples BOOLEAN DEFAULT FALSE,
    include_questions BOOLEAN DEFAULT FALSE,
    english_text LONGTEXT NOT NULL,
    french_text LONGTEXT,
    api_response_time FLOAT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_domain (domain),
    INDEX idx_topic (topic),
    INDEX idx_level (level),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLE: api_cache
-- Description: Cache des réponses API pour optimiser les performances
-- =========================================
CREATE TABLE IF NOT EXISTS api_cache (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cache_key VARCHAR(255) UNIQUE NOT NULL,
    response LONGTEXT NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_cache_key (cache_key),
    INDEX idx_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLE: api_logs
-- Description: Logs des requêtes API pour monitoring
-- =========================================
CREATE TABLE IF NOT EXISTS api_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    endpoint VARCHAR(255) NOT NULL,
    method VARCHAR(10) NOT NULL,
    request_data JSON,
    response_status INT,
    response_time FLOAT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_endpoint (endpoint),
    INDEX idx_method (method),
    INDEX idx_response_status (response_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- TABLE: user_sessions
-- Description: Sessions utilisateur (optionnel)
-- =========================================
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session_id (session_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- INSERTION DES DONNÉES PAR DÉFAUT
-- =========================================

-- Insertion des domaines
INSERT IGNORE INTO domains (name, description, is_active) VALUES
('Technologie', 'Explorez le monde fascinant de la technologie moderne et ses innovations.', TRUE),
('Sciences', 'Découvrez les merveilles de la science et les avancées de la recherche.', TRUE),
('Business', 'Plongez dans l\'univers du commerce, de l\'entrepreneuriat et de la finance.', TRUE),
('Santé', 'Apprenez tout sur la santé, le bien-être et les soins médicaux.', TRUE),
('Arts', 'Explorez le monde créatif des arts, de la musique et de la littérature.', TRUE),
('Voyage', 'Découvrez des destinations extraordinaires et des expériences de voyage uniques.', TRUE),
('Sport', 'Plongez dans l\'univers passionnant du sport et de la compétition.', TRUE),
('Histoire', 'Explorez les événements passés qui ont façonné notre monde.', TRUE),
('Cuisine', 'Découvrez l\'art culinaire et les saveurs du monde entier.', TRUE);

-- Insertion des sujets pour chaque domaine
-- Technologie
INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Intelligence Artificielle', 'Découvrez les bases de l\'IA et ses applications modernes.', TRUE
FROM domains d WHERE d.name = 'Technologie';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Développement Web', 'Apprenez les technologies du web moderne.', TRUE
FROM domains d WHERE d.name = 'Technologie';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Cybersécurité', 'Comprenez les enjeux de la sécurité informatique.', TRUE
FROM domains d WHERE d.name = 'Technologie';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Blockchain', 'Explorez la technologie des chaînes de blocs.', TRUE
FROM domains d WHERE d.name = 'Technologie';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Développement Mobile', 'Créez des applications mobiles innovantes.', TRUE
FROM domains d WHERE d.name = 'Technologie';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Cloud Computing', 'Maîtrisez les technologies du cloud.', TRUE
FROM domains d WHERE d.name = 'Technologie';

-- Sciences
INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Astronomie', 'Explorez l\'univers et ses mystères.', TRUE
FROM domains d WHERE d.name = 'Sciences';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Biologie', 'Découvrez le monde du vivant.', TRUE
FROM domains d WHERE d.name = 'Sciences';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Physique', 'Comprenez les lois de la physique.', TRUE
FROM domains d WHERE d.name = 'Sciences';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Chimie', 'Explorez la matière et ses transformations.', TRUE
FROM domains d WHERE d.name = 'Sciences';

-- Business
INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Marketing Digital', 'Maîtrisez les stratégies marketing en ligne.', TRUE
FROM domains d WHERE d.name = 'Business';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Entrepreneuriat', 'Lancez et développez votre entreprise.', TRUE
FROM domains d WHERE d.name = 'Business';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Finance', 'Comprenez la gestion financière.', TRUE
FROM domains d WHERE d.name = 'Business';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Management', 'Développez vos compétences de leadership.', TRUE
FROM domains d WHERE d.name = 'Business';

-- Santé
INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Nutrition', 'Apprenez les principes d\'une alimentation saine.', TRUE
FROM domains d WHERE d.name = 'Santé';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Fitness', 'Découvrez les bienfaits de l\'activité physique.', TRUE
FROM domains d WHERE d.name = 'Santé';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Médecine', 'Explorez le monde médical.', TRUE
FROM domains d WHERE d.name = 'Santé';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Bien-être Mental', 'Comprenez l\'importance de la santé mentale.', TRUE
FROM domains d WHERE d.name = 'Santé';

-- Arts
INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Peinture', 'Découvrez les techniques et l\'histoire de la peinture.', TRUE
FROM domains d WHERE d.name = 'Arts';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Musique', 'Explorez le monde des sons et des mélodies.', TRUE
FROM domains d WHERE d.name = 'Arts';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Littérature', 'Plongez dans l\'univers des mots et des histoires.', TRUE
FROM domains d WHERE d.name = 'Arts';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Cinéma', 'Découvrez l\'art du septième art.', TRUE
FROM domains d WHERE d.name = 'Arts';

-- Voyage
INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Destinations Exotiques', 'Explorez des lieux extraordinaires.', TRUE
FROM domains d WHERE d.name = 'Voyage';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Aventure et Nature', 'Vivez des expériences uniques en pleine nature.', TRUE
FROM domains d WHERE d.name = 'Voyage';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Voyage Urbain', 'Découvrez les villes du monde.', TRUE
FROM domains d WHERE d.name = 'Voyage';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Conseils de Voyage', 'Apprenez à voyager intelligemment.', TRUE
FROM domains d WHERE d.name = 'Voyage';

-- Sport
INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Football', 'Découvrez l\'univers du football mondial.', TRUE
FROM domains d WHERE d.name = 'Sport';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Basketball', 'Explorez le monde du basketball.', TRUE
FROM domains d WHERE d.name = 'Sport';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Tennis', 'Maîtrisez les techniques du tennis.', TRUE
FROM domains d WHERE d.name = 'Sport';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Sports Olympiques', 'Découvrez les Jeux Olympiques.', TRUE
FROM domains d WHERE d.name = 'Sport';

-- Histoire
INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Histoire Ancienne', 'Explorez les civilisations antiques.', TRUE
FROM domains d WHERE d.name = 'Histoire';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Histoire Moderne', 'Comprenez l\'époque contemporaine.', TRUE
FROM domains d WHERE d.name = 'Histoire';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Histoire Contemporaine', 'Découvrez les événements récents.', TRUE
FROM domains d WHERE d.name = 'Histoire';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Biographies', 'Découvrez des personnages historiques.', TRUE
FROM domains d WHERE d.name = 'Histoire';

-- Cuisine
INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Cuisine Française', 'Maîtrisez l\'art culinaire français.', TRUE
FROM domains d WHERE d.name = 'Cuisine';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Cuisine Internationale', 'Explorez les cuisines du monde.', TRUE
FROM domains d WHERE d.name = 'Cuisine';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Pâtisserie', 'Devenez un expert en desserts.', TRUE
FROM domains d WHERE d.name = 'Cuisine';

INSERT IGNORE INTO topics (domain_id, name, description, is_active)
SELECT d.id, 'Cuisine Saine', 'Apprenez à cuisiner équilibré.', TRUE
FROM domains d WHERE d.name = 'Cuisine';

-- =========================================
-- OPTIMISATIONS ET INDEX
-- =========================================

-- Optimisation des performances
ALTER TABLE generated_texts ADD FULLTEXT INDEX idx_fulltext_english (english_text);
ALTER TABLE generated_texts ADD FULLTEXT INDEX idx_fulltext_french (french_text);

-- Statistiques pour les analyses
CREATE OR REPLACE VIEW domain_stats AS
SELECT
    d.name as domain_name,
    COUNT(DISTINCT t.id) as topics_count,
    COUNT(DISTINCT gt.id) as texts_generated,
    AVG(gt.api_response_time) as avg_response_time,
    MAX(gt.created_at) as last_generation
FROM domains d
LEFT JOIN topics t ON d.id = t.domain_id AND t.is_active = TRUE
LEFT JOIN generated_texts gt ON d.name = gt.domain
WHERE d.is_active = TRUE
GROUP BY d.id, d.name;

-- =========================================
-- FIN DU SCHÉMA
-- =========================================