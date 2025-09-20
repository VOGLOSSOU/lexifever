<?php
/**
 * Classe Database - Gestionnaire de base de données
 * Utilise le pattern Singleton pour une connexion unique
 */

class Database {
    private static $instance = null;
    private $pdo;
    private $config;

    /**
     * Constructeur privé (Singleton)
     */
    private function __construct() {
        $this->config = require __DIR__ . '/../Config/config.php';

        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $this->config['database']['host'],
                $this->config['database']['name'],
                $this->config['database']['charset']
            );

            $this->pdo = new PDO(
                $dsn,
                $this->config['database']['user'],
                $this->config['database']['pass'],
                $this->config['database']['options']
            );

            // Log de connexion réussie
            $this->log("Connexion à la base de données établie");

        } catch (PDOException $e) {
            $this->log("Erreur de connexion à la base de données: " . $e->getMessage());
            throw new Exception("Erreur de connexion à la base de données");
        }
    }

    /**
     * Obtenir l'instance unique (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Exécuter une requête préparée
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->log("Erreur SQL: " . $e->getMessage() . " - SQL: " . $sql);
            throw new Exception("Erreur lors de l'exécution de la requête");
        }
    }

    /**
     * Récupérer tous les résultats
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un seul résultat
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Insérer des données générique
     */
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $this->query($sql, $data);
        return $this->pdo->lastInsertId();
    }

    /**
     * Mettre à jour des données générique
     */
    public function update($table, $data, $where) {
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setParts);

        $whereParts = [];
        foreach (array_keys($where) as $column) {
            $whereParts[] = "{$column} = :where_{$column}";
        }
        $whereClause = implode(' AND ', $whereParts);

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";

        // Préparer les paramètres
        $params = $data;
        foreach ($where as $key => $value) {
            $params["where_{$key}"] = $value;
        }

        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Supprimer des données générique
     */
    public function delete($table, $where) {
        $whereParts = [];
        foreach (array_keys($where) as $column) {
            $whereParts[] = "{$column} = :{$column}";
        }
        $whereClause = implode(' AND ', $whereParts);

        $sql = "DELETE FROM {$table} WHERE {$whereClause}";

        $stmt = $this->query($sql, $where);
        return $stmt->rowCount();
    }

    /**
     * Vérifier si une table existe
     */
    public function tableExists($tableName) {
        try {
            $result = $this->query("SHOW TABLES LIKE ?", [$tableName]);
            return $result->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Créer les tables si elles n'existent pas
     */
    public function createTablesIfNotExist() {
        $tables = [
            'domains' => "
                CREATE TABLE IF NOT EXISTS domains (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    name VARCHAR(100) NOT NULL UNIQUE,
                    description TEXT,
                    image_url VARCHAR(255),
                    is_active BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ",
            'topics' => "
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
                    UNIQUE KEY unique_domain_topic (domain_id, name)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ",
            'generated_texts' => "
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
                    INDEX idx_created_at (created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ",
            'api_cache' => "
                CREATE TABLE IF NOT EXISTS api_cache (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    cache_key VARCHAR(255) UNIQUE NOT NULL,
                    response LONGTEXT NOT NULL,
                    expires_at TIMESTAMP NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_cache_key (cache_key),
                    INDEX idx_expires_at (expires_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ",
            'api_logs' => "
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
                    INDEX idx_created_at (created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            "
        ];

        foreach ($tables as $tableName => $sql) {
            try {
                $this->pdo->exec($sql);
                $this->log("Table '{$tableName}' créée ou déjà existante");
            } catch (Exception $e) {
                $this->log("Erreur lors de la création de la table '{$tableName}': " . $e->getMessage());
            }
        }
    }

    /**
     * Logger un message
     */
    private function log($message) {
        if ($this->config['logs']['enabled']) {
            $logFile = $this->config['logs']['directory'] . '/database.log';
            $timestamp = date('Y-m-d H:i:s');
            $logMessage = "[{$timestamp}] {$message}\n";

            // Créer le dossier logs s'il n'existe pas
            if (!is_dir($this->config['logs']['directory'])) {
                mkdir($this->config['logs']['directory'], 0755, true);
            }

            file_put_contents($logFile, $logMessage, FILE_APPEND);
        }
    }

    /**
     * Obtenir la connexion PDO (pour les cas spéciaux)
     */
    public function getPdo() {
        return $this->pdo;
    }

    /**
     * Fermer la connexion (optionnel, PHP le fait automatiquement)
     */
    public function close() {
        $this->pdo = null;
        self::$instance = null;
    }
}