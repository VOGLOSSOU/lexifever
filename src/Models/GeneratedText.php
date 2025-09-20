<?php
/**
 * Classe GeneratedText - Modèle pour les textes générés
 * Gère le stockage et la récupération des textes générés
 */

class GeneratedText {
    private $db;
    private $config;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->config = require __DIR__ . '/../Config/config.php';
    }

    /**
     * Créer un nouveau texte généré
     */
    public function create($data) {
        try {
            // Validation des données
            if (empty($data['session_id'])) {
                throw new Exception("L'ID de session est obligatoire");
            }

            if (empty($data['english_text'])) {
                throw new Exception("Le texte anglais est obligatoire");
            }

            $insertData = [
                'session_id' => $data['session_id'],
                'domain' => $data['domain'] ?? '',
                'topic' => $data['topic'] ?? '',
                'level' => $data['level'] ?? 'intermediate',
                'tone' => $data['tone'] ?? 'informative',
                'length' => $data['length'] ?? 'medium',
                'include_examples' => $data['include_examples'] ?? false,
                'include_questions' => $data['include_questions'] ?? false,
                'english_text' => $data['english_text'],
                'french_text' => $data['french_text'] ?? '',
                'api_response_time' => $data['api_response_time'] ?? null
            ];

            $id = $this->db->insert('generated_texts', $insertData);
            return $id;

        } catch (Exception $e) {
            error_log("Erreur lors de la création du texte généré: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Récupérer un texte par son ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM generated_texts WHERE id = ?";
            return $this->db->fetchOne($sql, [$id]);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération du texte {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer les textes d'une session
     */
    public function getBySession($sessionId, $limit = 50, $offset = 0) {
        try {
            $sql = "SELECT id, domain, topic, level, tone, length, english_text, french_text, created_at
                    FROM generated_texts
                    WHERE session_id = ?
                    ORDER BY created_at DESC
                    LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$sessionId, $limit, $offset]);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des textes de la session {$sessionId}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mettre à jour la traduction d'un texte
     */
    public function updateTranslation($id, $frenchText) {
        try {
            return $this->db->update(
                'generated_texts',
                ['french_text' => $frenchText],
                ['id' => $id]
            );
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour de la traduction du texte {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprimer un texte
     */
    public function delete($id) {
        try {
            return $this->db->delete('generated_texts', ['id' => $id]);
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression du texte {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprimer tous les textes d'une session
     */
    public function deleteBySession($sessionId) {
        try {
            return $this->db->delete('generated_texts', ['session_id' => $sessionId]);
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression des textes de la session {$sessionId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Compter le nombre de textes d'une session
     */
    public function countBySession($sessionId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM generated_texts WHERE session_id = ?";
            $result = $this->db->fetchOne($sql, [$sessionId]);
            return (int)$result['count'];
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des textes de la session {$sessionId}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupérer les textes récents (pour les statistiques)
     */
    public function getRecent($limit = 10) {
        try {
            $sql = "SELECT id, domain, topic, level, created_at
                    FROM generated_texts
                    ORDER BY created_at DESC
                    LIMIT ?";
            return $this->db->fetchAll($sql, [$limit]);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des textes récents: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Rechercher des textes
     */
    public function search($query, $sessionId = null, $limit = 20) {
        try {
            $sql = "SELECT id, domain, topic, level, tone, english_text, french_text, created_at
                    FROM generated_texts
                    WHERE (english_text LIKE ? OR french_text LIKE ? OR domain LIKE ? OR topic LIKE ?)";

            $params = ["%{$query}%", "%{$query}%", "%{$query}%", "%{$query}%"];

            if ($sessionId) {
                $sql .= " AND session_id = ?";
                $params[] = $sessionId;
            }

            $sql .= " ORDER BY created_at DESC LIMIT ?";
            $params[] = $limit;

            return $this->db->fetchAll($sql, $params);

        } catch (Exception $e) {
            error_log("Erreur lors de la recherche de textes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtenir les statistiques des textes générés
     */
    public function getStats() {
        try {
            $stats = [];

            // Nombre total de textes
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM generated_texts");
            $stats['total_texts'] = (int)$result['count'];

            // Textes générés aujourd'hui
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM generated_texts WHERE DATE(created_at) = CURDATE()");
            $stats['texts_today'] = (int)$result['count'];

            // Textes générés cette semaine
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM generated_texts WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
            $stats['texts_this_week'] = (int)$result['count'];

            // Domaines les plus populaires
            $sql = "SELECT domain, COUNT(*) as count
                    FROM generated_texts
                    WHERE domain != ''
                    GROUP BY domain
                    ORDER BY count DESC
                    LIMIT 5";
            $stats['popular_domains'] = $this->db->fetchAll($sql);

            // Niveaux les plus utilisés
            $sql = "SELECT level, COUNT(*) as count
                    FROM generated_texts
                    GROUP BY level
                    ORDER BY count DESC";
            $stats['level_distribution'] = $this->db->fetchAll($sql);

            // Temps de réponse moyen
            $result = $this->db->fetchOne("SELECT AVG(api_response_time) as avg_time FROM generated_texts WHERE api_response_time IS NOT NULL");
            $stats['avg_response_time'] = $result['avg_time'] ? round((float)$result['avg_time'], 2) : null;

            return $stats;

        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des statistiques: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Nettoyer les anciens textes (pour économiser l'espace)
     */
    public function cleanupOldTexts($daysOld = 90) {
        try {
            $sql = "DELETE FROM generated_texts WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
            $result = $this->db->query($sql, [$daysOld]);
            $deleted = $result->rowCount();

            if ($deleted > 0) {
                error_log("Nettoyé {$deleted} textes de plus de {$daysOld} jours");
            }

            return $deleted;

        } catch (Exception $e) {
            error_log("Erreur lors du nettoyage des anciens textes: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un texte existe
     */
    public function exists($id) {
        try {
            $result = $this->db->fetchOne("SELECT 1 FROM generated_texts WHERE id = ?", [$id]);
            return $result !== false;
        } catch (Exception $e) {
            error_log("Erreur lors de la vérification du texte {$id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les textes d'un domaine spécifique
     */
    public function getByDomain($domain, $limit = 20) {
        try {
            $sql = "SELECT id, topic, level, tone, created_at
                    FROM generated_texts
                    WHERE domain = ?
                    ORDER BY created_at DESC
                    LIMIT ?";
            return $this->db->fetchAll($sql, [$domain, $limit]);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des textes du domaine '{$domain}': " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtenir les textes d'un sujet spécifique
     */
    public function getByTopic($topic, $limit = 20) {
        try {
            $sql = "SELECT id, domain, level, tone, created_at
                    FROM generated_texts
                    WHERE topic = ?
                    ORDER BY created_at DESC
                    LIMIT ?";
            return $this->db->fetchAll($sql, [$topic, $limit]);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des textes du sujet '{$topic}': " . $e->getMessage());
            return [];
        }
    }

    /**
     * Exporter les textes d'une session au format JSON
     */
    public function exportSession($sessionId) {
        try {
            $texts = $this->getBySession($sessionId, 1000); // Récupérer tous les textes

            $export = [
                'session_id' => $sessionId,
                'export_date' => date('c'),
                'total_texts' => count($texts),
                'texts' => $texts
            ];

            return json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erreur lors de l'exportation de la session {$sessionId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Importer des textes depuis un export JSON
     */
    public function importSession($jsonData) {
        try {
            $data = json_decode($jsonData, true);

            if (!$data || !isset($data['texts'])) {
                throw new Exception("Format de données invalide");
            }

            $imported = 0;
            foreach ($data['texts'] as $text) {
                // Créer le texte avec les données importées
                $this->create($text);
                $imported++;
            }

            return $imported;

        } catch (Exception $e) {
            error_log("Erreur lors de l'importation des textes: " . $e->getMessage());
            throw $e;
        }
    }
}