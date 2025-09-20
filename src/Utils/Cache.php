<?php
/**
 * Classe Cache - Gestionnaire de cache pour les réponses API
 * Utilise la base de données pour stocker les réponses en cache
 */

class Cache {
    private $db;
    private $config;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->config = require __DIR__ . '/../Config/config.php';
    }

    /**
     * Générer une clé de cache unique
     */
    public function generateKey($data) {
        // Trier les données pour une clé cohérente
        ksort($data);
        return 'cache_' . md5(json_encode($data));
    }

    /**
     * Vérifier si le cache est activé
     */
    private function isEnabled() {
        return $this->config['cache']['enabled'] ?? true;
    }

    /**
     * Récupérer une valeur du cache
     */
    public function get($key) {
        if (!$this->isEnabled()) {
            return null;
        }

        try {
            $sql = "SELECT response FROM api_cache
                    WHERE cache_key = ? AND expires_at > NOW()";
            $result = $this->db->fetchOne($sql, [$key]);

            if ($result) {
                $this->log("Cache hit pour la clé: {$key}");
                return json_decode($result['response'], true);
            }

            $this->log("Cache miss pour la clé: {$key}");
            return null;

        } catch (Exception $e) {
            $this->log("Erreur lors de la récupération du cache: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Stocker une valeur en cache
     */
    public function set($key, $value, $ttl = null) {
        if (!$this->isEnabled()) {
            return false;
        }

        // Utiliser la TTL par défaut si non spécifiée
        if ($ttl === null) {
            $ttl = $this->config['cache']['default_ttl'];
        }

        try {
            // Calculer la date d'expiration
            $expiresAt = date('Y-m-d H:i:s', time() + $ttl);

            // Encoder la valeur en JSON
            $jsonValue = json_encode($value);

            // Insérer ou mettre à jour le cache
            $sql = "INSERT INTO api_cache (cache_key, response, expires_at)
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                        response = VALUES(response),
                        expires_at = VALUES(expires_at)";

            $this->db->query($sql, [$key, $jsonValue, $expiresAt]);

            $this->log("Cache set pour la clé: {$key} (TTL: {$ttl}s)");
            return true;

        } catch (Exception $e) {
            $this->log("Erreur lors du stockage en cache: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer une entrée du cache
     */
    public function delete($key) {
        try {
            $sql = "DELETE FROM api_cache WHERE cache_key = ?";
            $result = $this->db->query($sql, [$key]);

            $deleted = $result->rowCount() > 0;
            if ($deleted) {
                $this->log("Cache supprimé pour la clé: {$key}");
            }

            return $deleted;

        } catch (Exception $e) {
            $this->log("Erreur lors de la suppression du cache: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vider tout le cache
     */
    public function clear() {
        try {
            $sql = "DELETE FROM api_cache";
            $this->db->query($sql);

            $this->log("Cache vidé complètement");
            return true;

        } catch (Exception $e) {
            $this->log("Erreur lors du vidage du cache: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Nettoyer les entrées expirées
     */
    public function cleanExpired() {
        try {
            $sql = "DELETE FROM api_cache WHERE expires_at <= NOW()";
            $result = $this->db->query($sql);

            $deleted = $result->rowCount();
            if ($deleted > 0) {
                $this->log("Nettoyé {$deleted} entrées de cache expirées");
            }

            return $deleted;

        } catch (Exception $e) {
            $this->log("Erreur lors du nettoyage du cache: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si une clé existe dans le cache
     */
    public function has($key) {
        try {
            $sql = "SELECT 1 FROM api_cache
                    WHERE cache_key = ? AND expires_at > NOW()
                    LIMIT 1";
            $result = $this->db->fetchOne($sql, [$key]);

            return $result !== false;

        } catch (Exception $e) {
            $this->log("Erreur lors de la vérification du cache: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les statistiques du cache
     */
    public function getStats() {
        try {
            // Nombre total d'entrées
            $total = $this->db->fetchOne("SELECT COUNT(*) as count FROM api_cache")['count'];

            // Nombre d'entrées actives
            $active = $this->db->fetchOne("SELECT COUNT(*) as count FROM api_cache WHERE expires_at > NOW()")['count'];

            // Nombre d'entrées expirées
            $expired = $total - $active;

            // Taille estimée (approximation)
            $sizeResult = $this->db->fetchOne("SELECT SUM(LENGTH(response)) as size FROM api_cache");
            $size = $sizeResult['size'] ?? 0;

            return [
                'total_entries' => (int)$total,
                'active_entries' => (int)$active,
                'expired_entries' => (int)$expired,
                'estimated_size_bytes' => (int)$size,
                'estimated_size_mb' => round($size / 1024 / 1024, 2)
            ];

        } catch (Exception $e) {
            $this->log("Erreur lors de la récupération des statistiques: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Cache avec génération automatique
     */
    public function remember($key, $ttl, $callback) {
        // Vérifier d'abord le cache
        $cached = $this->get($key);
        if ($cached !== null) {
            return $cached;
        }

        // Générer la valeur
        $value = $callback();

        // Stocker en cache
        $this->set($key, $value, $ttl);

        return $value;
    }

    /**
     * Logger un message
     */
    private function log($message) {
        if ($this->config['logs']['enabled']) {
            $logFile = $this->config['logs']['directory'] . '/cache.log';
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
     * Optimiser le cache (nettoyer et analyser)
     */
    public function optimize() {
        $this->log("Début de l'optimisation du cache");

        // Nettoyer les entrées expirées
        $cleaned = $this->cleanExpired();

        // Obtenir les statistiques
        $stats = $this->getStats();

        $this->log("Optimisation terminée - {$cleaned} entrées nettoyées");

        return [
            'cleaned_entries' => $cleaned,
            'stats' => $stats
        ];
    }
}