<?php
/**
 * Classe ApiController - Contrôleur principal de l'API
 * Gère toutes les requêtes API REST
 */

class ApiController {
    private $geminiService;
    private $domainModel;
    private $textModel;
    private $validator;
    private $config;

    public function __construct() {
        $this->config = require __DIR__ . '/../Config/config.php';
        $this->geminiService = new GeminiAIService();
        $this->domainModel = new Domain();
        $this->textModel = new GeneratedText();
        $this->validator = new Validator();
    }

    /**
     * Endpoint de santé de l'API
     */
    public function health() {
        try {
            // Vérifier la connexion à la base de données
            $db = Database::getInstance();
            $dbHealth = true;

            // Vérifier l'API Gemini
            $geminiHealth = $this->geminiService->healthCheck();

            $response = [
                'status' => ($dbHealth && $geminiHealth['status'] === 'OK') ? 'OK' : 'ERROR',
                'message' => 'API Lexifever ' . (($dbHealth && $geminiHealth['status'] === 'OK') ? 'fonctionnelle' : 'avec problèmes'),
                'timestamp' => date('c'),
                'version' => $this->config['app']['version'],
                'services' => [
                    'database' => $dbHealth ? 'OK' : 'ERROR',
                    'gemini_api' => $geminiHealth['status'],
                    'cache' => $this->config['cache']['enabled'] ? 'ENABLED' : 'DISABLED'
                ]
            ];

            ApiResponse::success($response);

        } catch (Exception $e) {
            ApiResponse::error('Erreur lors de la vérification de santé: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Générer un texte personnalisé
     */
    public function generateText() {
        try {
            // Récupérer et valider les données JSON
            $data = $this->validator->validateJsonInput();
            if (!$data) {
                ApiResponse::error('Données JSON invalides', 400);
                return;
            }

            // Valider les paramètres
            if (!$this->validator->validateGenerateText($data)) {
                ApiResponse::validationError($this->validator->getErrors());
                return;
            }

            // Générer un ID de session si non fourni
            if (empty($data['session_id'])) {
                $data['session_id'] = $this->generateSessionId();
            }

            // Mesurer le temps de réponse
            $startTime = microtime(true);

            // Générer le texte avec Gemini
            $result = $this->geminiService->generateText($data);

            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000, 2); // en millisecondes

            // Sauvegarder en base de données
            $textData = array_merge($data, [
                'english_text' => $result['englishText'],
                'api_response_time' => $responseTime
            ]);

            $textId = $this->textModel->create($textData);

            // Préparer la réponse
            $response = [
                'id' => $textId,
                'englishText' => $result['englishText'],
                'parameters' => $result['parameters'],
                'timestamp' => $result['timestamp'],
                'response_time_ms' => $responseTime
            ];

            ApiResponse::success($response);

        } catch (Exception $e) {
            error_log("Erreur lors de la génération de texte: " . $e->getMessage());
            ApiResponse::error('Erreur lors de la génération du texte: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Traduire un texte
     */
    public function translate() {
        try {
            // Récupérer et valider les données JSON
            $data = $this->validator->validateJsonInput();
            if (!$data) {
                ApiResponse::error('Données JSON invalides', 400);
                return;
            }

            // Valider les paramètres
            if (!$this->validator->validateTranslation($data)) {
                ApiResponse::validationError($this->validator->getErrors());
                return;
            }

            // Traduire le texte
            $result = $this->geminiService->translateText($data['text'], $data['target_language'] ?? 'french');

            // Si un ID de texte est fourni, mettre à jour la traduction
            if (!empty($data['text_id'])) {
                try {
                    $this->textModel->updateTranslation($data['text_id'], $result['translatedText']);
                } catch (Exception $e) {
                    error_log("Erreur lors de la mise à jour de la traduction: " . $e->getMessage());
                }
            }

            ApiResponse::success($result);

        } catch (Exception $e) {
            error_log("Erreur lors de la traduction: " . $e->getMessage());
            ApiResponse::error('Erreur lors de la traduction: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Récupérer la liste des domaines
     */
    public function getDomains() {
        try {
            $domains = $this->domainModel->getAll();

            // Ajouter le nombre de sujets pour chaque domaine
            foreach ($domains as &$domain) {
                $topics = $this->domainModel->getTopics($domain['id']);
                $domain['topics_count'] = count($topics);
            }

            ApiResponse::success($domains);

        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des domaines: " . $e->getMessage());
            ApiResponse::error('Erreur lors de la récupération des domaines', 500);
        }
    }

    /**
     * Récupérer les sujets d'un domaine
     */
    public function getTopics($domainId) {
        try {
            // Valider l'ID du domaine
            if (!$this->validator->validateDomainId($domainId)) {
                ApiResponse::validationError($this->validator->getErrors());
                return;
            }

            // Vérifier que le domaine existe
            if (!$this->domainModel->exists($domainId)) {
                ApiResponse::notFound('Domaine non trouvé');
                return;
            }

            $topics = $this->domainModel->getTopics($domainId);
            ApiResponse::success($topics);

        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des sujets: " . $e->getMessage());
            ApiResponse::error('Erreur lors de la récupération des sujets', 500);
        }
    }

    /**
     * Récupérer l'historique des textes
     */
    public function getHistory() {
        try {
            // Récupérer les paramètres de requête
            $sessionId = $_GET['session_id'] ?? '';
            $limit = (int)($_GET['limit'] ?? 20);
            $offset = (int)($_GET['offset'] ?? 0);

            // Valider les paramètres
            if (!$this->validator->validateSessionId($sessionId)) {
                ApiResponse::validationError($this->validator->getErrors());
                return;
            }

            // Limiter les résultats
            $limit = min($limit, 100); // Maximum 100 résultats
            $offset = max($offset, 0);

            $texts = $this->textModel->getBySession($sessionId, $limit, $offset);
            $total = $this->textModel->countBySession($sessionId);

            $response = [
                'texts' => $texts,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'has_more' => ($offset + $limit) < $total
            ];

            ApiResponse::success($response);

        } catch (Exception $e) {
            error_log("Erreur lors de la récupération de l'historique: " . $e->getMessage());
            ApiResponse::error('Erreur lors de la récupération de l\'historique', 500);
        }
    }

    /**
     * Supprimer un texte de l'historique
     */
    public function deleteText($textId) {
        try {
            // Valider l'ID du texte
            if (!$this->validator->validateTextId($textId)) {
                ApiResponse::validationError($this->validator->getErrors());
                return;
            }

            // Vérifier que le texte existe
            if (!$this->textModel->exists($textId)) {
                ApiResponse::notFound('Texte non trouvé');
                return;
            }

            $deleted = $this->textModel->delete($textId);

            if ($deleted) {
                ApiResponse::success(['message' => 'Texte supprimé avec succès']);
            } else {
                ApiResponse::error('Erreur lors de la suppression du texte', 500);
            }

        } catch (Exception $e) {
            error_log("Erreur lors de la suppression du texte: " . $e->getMessage());
            ApiResponse::error('Erreur lors de la suppression du texte', 500);
        }
    }

    /**
     * Obtenir les statistiques
     */
    public function getStats() {
        try {
            $stats = [
                'texts' => $this->textModel->getStats(),
                'domains' => $this->domainModel->getStats(),
                'generated_at' => date('c')
            ];

            ApiResponse::success($stats);

        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des statistiques: " . $e->getMessage());
            ApiResponse::error('Erreur lors de la récupération des statistiques', 500);
        }
    }

    /**
     * Recherche de textes
     */
    public function search() {
        try {
            $query = $_GET['q'] ?? '';
            $sessionId = $_GET['session_id'] ?? null;
            $limit = (int)($_GET['limit'] ?? 20);

            if (empty($query)) {
                ApiResponse::error('Paramètre de recherche requis', 400);
                return;
            }

            if (strlen($query) < 2) {
                ApiResponse::error('La recherche doit contenir au moins 2 caractères', 400);
                return;
            }

            $results = $this->textModel->search($query, $sessionId, min($limit, 50));

            ApiResponse::success([
                'query' => $query,
                'results' => $results,
                'total' => count($results)
            ]);

        } catch (Exception $e) {
            error_log("Erreur lors de la recherche: " . $e->getMessage());
            ApiResponse::error('Erreur lors de la recherche', 500);
        }
    }

    /**
     * Générer un ID de session unique
     */
    private function generateSessionId() {
        return 'session_' . bin2hex(random_bytes(16)) . '_' . time();
    }

    /**
     * Gérer les requêtes OPTIONS (CORS preflight)
     */
    public function handleOptions() {
        ApiResponse::handleOptions();
    }

    /**
     * Vérifier le rate limiting
     */
    private function checkRateLimit() {
        // Cette méthode pourrait être étendue pour implémenter un rate limiting
        // Pour l'instant, on utilise la configuration de base
        return true;
    }

    /**
     * Logger les requêtes API
     */
    private function logRequest($endpoint, $method, $responseStatus, $responseTime) {
        if ($this->config['logs']['enabled']) {
            try {
                $db = Database::getInstance();
                $logData = [
                    'endpoint' => $endpoint,
                    'method' => $method,
                    'response_status' => $responseStatus,
                    'response_time' => $responseTime,
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
                ];

                $db->insert('api_logs', $logData);
            } catch (Exception $e) {
                error_log("Erreur lors du logging de la requête: " . $e->getMessage());
            }
        }
    }
}