# 📋 MODÉLISATION LEXIFEVER - VERSION PHP

## 🎯 Vue d'Ensemble

**Lexifever** est une application web pour l'enrichissement du vocabulaire anglais utilisant l'IA pour générer des textes personnalisés avec traduction française.

### Architecture Cible
- **Frontend** : HTML/CSS/JS vanilla (conservé)
- **Backend** : PHP natif avec API REST
- **Base de données** : MySQL
- **IA** : Google Gemini API (gratuit via Google AI Studio)

---

## 🗂️ STRUCTURE DES FICHIERS

```
lexifever-php/
├── 📁 public/                    # Point d'entrée web
│   ├── index.php                # Routeur principal
│   └── .htaccess                # Configuration Apache
├── 📁 src/                      # Code source PHP
│   ├── 📁 Controllers/          # Contrôleurs API
│   │   ├── ApiController.php
│   │   └── TextController.php
│   ├── 📁 Models/               # Modèles de données
│   │   ├── Domain.php
│   │   ├── Topic.php
│   │   ├── GeneratedText.php
│   │   └── User.php
│   ├── 📁 Services/             # Services métier
│   │   ├── GeminiAIService.php
│   │   ├── TranslationService.php
│   │   └── HistoryService.php
│   ├── 📁 Utils/                # Utilitaires
│   │   ├── Database.php
│   │   ├── ApiResponse.php
│   │   ├── Validator.php
│   │   └── Cache.php
│   └── 📁 Config/               # Configuration
│       └── config.php
├── 📁 frontend/                 # Interface utilisateur
├── 📁 logs/                     # Logs d'erreur
├── 📁 cache/                    # Cache des réponses
├── 📄 composer.json             # Dépendances PHP
└── 📄 README.md                 # Documentation
```

---

## 🗃️ SCHÉMA BASE DE DONNÉES

### Table: domains
```sql
CREATE TABLE domains (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image_url VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Table: topics
```sql
CREATE TABLE topics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    domain_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (domain_id) REFERENCES domains(id) ON DELETE CASCADE,
    UNIQUE KEY unique_domain_topic (domain_id, name)
);
```

### Table: generated_texts
```sql
CREATE TABLE generated_texts (
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
    INDEX idx_created_at (created_at)
);
```

### Table: api_cache
```sql
CREATE TABLE api_cache (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cache_key VARCHAR(255) UNIQUE NOT NULL,
    response LONGTEXT NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_cache_key (cache_key),
    INDEX idx_expires_at (expires_at)
);
```

### Table: api_logs
```sql
CREATE TABLE api_logs (
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
    INDEX idx_created_at (created_at)
);
```

---

## 🔌 API ENDPOINTS

### GET /api/health
**Description** : Vérification de l'état de l'API
**Réponse** :
```json
{
    "status": "OK",
    "message": "API Lexifever fonctionnelle",
    "timestamp": "2025-01-20T10:00:00Z",
    "version": "1.0.0"
}
```

### GET /api/domains
**Description** : Liste des domaines disponibles
**Réponse** :
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Technologie",
            "description": "Intelligence artificielle, développement web...",
            "image_url": "https://...",
            "topics_count": 6
        }
    ]
}
```

### GET /api/domains/{id}/topics
**Description** : Sujets d'un domaine spécifique
**Paramètres** : id (integer) - ID du domaine
**Réponse** :
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Intelligence Artificielle",
            "description": "Apprentissage automatique...",
            "image_url": "https://..."
        }
    ]
}
```

### POST /api/generate-text
**Description** : Génération de texte personnalisé
**Paramètres** :
```json
{
    "domain": "Technologie",
    "topic": "Intelligence Artificielle",
    "level": "intermediate",
    "tone": "informative",
    "length": "medium",
    "includeExamples": false,
    "includeQuestions": false
}
```
**Réponse** :
```json
{
    "success": true,
    "data": {
        "id": 123,
        "englishText": "Generated text content...",
        "parameters": {...},
        "timestamp": "2025-01-20T10:00:00Z"
    }
}
```

### POST /api/translate
**Description** : Traduction de texte anglais vers français
**Paramètres** :
```json
{
    "text": "Your English text here"
}
```
**Réponse** :
```json
{
    "success": true,
    "data": {
        "originalText": "Original English text",
        "translatedText": "Texte français traduit",
        "timestamp": "2025-01-20T10:00:00Z"
    }
}
```

### GET /api/history
**Description** : Historique des textes générés
**Paramètres** : session_id (string) - ID de session utilisateur
**Réponse** :
```json
{
    "success": true,
    "data": [
        {
            "id": 123,
            "domain": "Technologie",
            "topic": "IA",
            "english_text": "...",
            "french_text": "...",
            "created_at": "2025-01-20T10:00:00Z"
        }
    ]
}
```

---

## 📋 CLASSES PHP À IMPLÉMENTER

### 1. Database.php
```php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Configuration PDO
    }

    public static function getInstance() {
        // Singleton pattern
    }

    public function query($sql, $params = []) {
        // Exécution de requête préparée
    }

    public function insert($table, $data) {
        // Insertion générique
    }

    public function update($table, $data, $where) {
        // Mise à jour générique
    }

    public function delete($table, $where) {
        // Suppression générique
    }
}
```

### 2. ApiResponse.php
```php
class ApiResponse {
    public static function success($data = null, $message = 'OK', $statusCode = 200) {
        // Réponse JSON de succès
    }

    public static function error($message = 'Erreur', $statusCode = 400, $errors = []) {
        // Réponse JSON d'erreur
    }

    public static function notFound($message = 'Ressource non trouvée') {
        // Erreur 404
    }

    public static function serverError($message = 'Erreur interne du serveur') {
        // Erreur 500
    }
}
```

### 3. Validator.php
```php
class Validator {
    private $errors = [];

    public function validateGenerateText($data) {
        // Validation des paramètres de génération
    }

    public function validateTranslation($data) {
        // Validation des paramètres de traduction
    }

    public function hasErrors() {
        // Vérifie s'il y a des erreurs
    }

    public function getErrors() {
        // Retourne les erreurs
    }
}
```

### 4. Cache.php
```php
class Cache {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function get($key) {
        // Récupération depuis le cache
    }

    public function set($key, $value, $ttl = 3600) {
        // Stockage en cache
    }

    public function delete($key) {
        // Suppression du cache
    }

    public function clear() {
        // Vidage du cache
    }
}
```

### 5. TogetherAIService.php
```php
class TogetherAIService {
    private $apiKey;
    private $baseUrl = 'https://api.together.xyz/v1/chat/completions';
    private $cache;

    public function __construct() {
        $this->apiKey = $_ENV['TOGETHER_API_KEY'] ?? '';
        $this->cache = new Cache();
    }

    public function generateText($params) {
        // Génération de texte via API Together AI
    }

    public function translateText($text) {
        // Traduction via API Together AI
    }

    private function callApi($messages, $options = []) {
        // Appel générique à l'API
    }

    private function buildPrompt($params) {
        // Construction du prompt personnalisé
    }
}
```

### 6. Domain.php (Model)
```php
class Domain {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        // Récupération de tous les domaines
    }

    public function getById($id) {
        // Récupération d'un domaine par ID
    }

    public function getTopics($domainId) {
        // Récupération des sujets d'un domaine
    }

    public function create($data) {
        // Création d'un nouveau domaine
    }

    public function update($id, $data) {
        // Mise à jour d'un domaine
    }

    public function delete($id) {
        // Suppression d'un domaine
    }
}
```

### 7. GeneratedText.php (Model)
```php
class GeneratedText {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        // Création d'un nouveau texte généré
    }

    public function getBySession($sessionId) {
        // Récupération des textes d'une session
    }

    public function getById($id) {
        // Récupération d'un texte par ID
    }

    public function updateTranslation($id, $frenchText) {
        // Mise à jour de la traduction
    }

    public function delete($id) {
        // Suppression d'un texte
    }

    public function getStats() {
        // Statistiques des textes générés
    }
}
```

### 8. ApiController.php
```php
class ApiController {
    private $aiService;
    private $domainModel;
    private $textModel;
    private $validator;

    public function __construct() {
        $this->aiService = new TogetherAIService();
        $this->domainModel = new Domain();
        $this->textModel = new GeneratedText();
        $this->validator = new Validator();
    }

    public function health() {
        // Endpoint de santé
    }

    public function generateText() {
        // Génération de texte
    }

    public function translate() {
        // Traduction
    }

    public function getDomains() {
        // Liste des domaines
    }

    public function getTopics($domainId) {
        // Sujets d'un domaine
    }

    public function getHistory() {
        // Historique utilisateur
    }
}
```

---

## ⚙️ CONFIGURATION

### config.php
```php
<?php
return [
    'database' => [
        'host' => 'localhost',
        'name' => 'lexifever',
        'user' => 'votre_user',
        'pass' => 'votre_password',
        'charset' => 'utf8mb4'
    ],
    'api' => [
        'together_ai_key' => 'votre_clé_api',
        'cache_duration' => 3600, // 1 heure
        'rate_limit' => 100, // requêtes par heure
        'timeout' => 30 // secondes
    ],
    'app' => [
        'debug' => true,
        'log_errors' => true,
        'timezone' => 'Europe/Paris'
    ],
    'cors' => [
        'allowed_origins' => ['http://localhost:3000', 'https://votredomaine.com'],
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With']
    ]
];
```

### .htaccess
```apache
RewriteEngine On

# Redirection vers index.php pour les routes API
RewriteRule ^api/(.*)$ index.php?api=$1 [QSA,L]

# Protection des fichiers sensibles
<Files "config.php">
    Order Allow,Deny
    Deny from all
</Files>

# Headers de sécurité
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
```

---

## 🔄 FLUX UTILISATEUR

1. **Page d'accueil** (`index.html`)
   - Présentation de Lexifever
   - Bouton "Commencer"

2. **Sélection domaine** (`select-domain.html`)
   - API GET /api/domains
   - Affichage des 9 domaines

3. **Sélection sujet** (`select-topic.html`)
   - API GET /api/domains/{id}/topics
   - Affichage des sujets du domaine

4. **Personnalisation** (`customize-text.html`)
   - Formulaire de configuration
   - Stockage en localStorage

5. **Génération** (`result.html`)
   - API POST /api/generate-text
   - Affichage du texte anglais
   - API POST /api/translate
   - Affichage de la traduction

6. **Historique** (`history.html`)
   - API GET /api/history
   - Affichage des textes précédents

---

## 📦 DÉPENDANCES PHP (composer.json)

```json
{
    "name": "lexifever/lexifever-php",
    "description": "Application d'enrichissement du vocabulaire anglais en PHP",
    "type": "project",
    "require": {
        "php": ">=8.1",
        "ext-pdo": "*",
        "ext-curl": "*",
        "ext-json": "*",
        "ext-mbstring": "*"
    },
    "autoload": {
        "psr-4": {
            "Lexifever\\": "src/"
        }
    }
}
```

---

## 🚀 PLAN DE DÉPLOIEMENT

### Prérequis
- PHP 8.1+
- MySQL 5.7+ ou MariaDB 10.0+
- Apache/Nginx avec mod_rewrite
- Extension PDO MySQL
- Extension cURL

### Étapes de déploiement
1. Upload des fichiers sur l'hébergement
2. Création de la base de données
3. Configuration de config.php
4. Test de l'API
5. Configuration du domaine

---

## ✅ CHECKLIST DE VALIDATION

- [ ] Structure des fichiers créée
- [ ] Base de données configurée
- [ ] Classes PHP implémentées
- [ ] API endpoints fonctionnels
- [ ] Intégration Together AI
- [ ] Gestion des erreurs
- [ ] Sécurité (CORS, validation, rate limiting)
- [ ] Cache opérationnel
- [ ] Logs configurés
- [ ] Tests manuels passés
- [ ] Déploiement réussi

---

*Modélisation créée le 20 janvier 2025 - Version 1.0*