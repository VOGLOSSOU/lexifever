<?php
/**
 * Classe ApiResponse - Gestionnaire des réponses API
 * Fournit des méthodes standardisées pour les réponses JSON
 */

class ApiResponse {
    /**
     * Configuration CORS
     */
    private static function setCorsHeaders() {
        $config = require __DIR__ . '/../Config/config.php';

        // Headers CORS
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            if (in_array($_SERVER['HTTP_ORIGIN'], $config['cors']['allowed_origins']) ||
                in_array('*', $config['cors']['allowed_origins'])) {
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            }
        }

        header("Access-Control-Allow-Methods: " . implode(', ', $config['cors']['allowed_methods']));
        header("Access-Control-Allow-Headers: " . implode(', ', $config['cors']['allowed_headers']));
        header("Access-Control-Max-Age: " . $config['cors']['max_age']);

        // Headers de sécurité
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: DENY");
        header("X-XSS-Protection: 1; mode=block");
        header("Referrer-Policy: strict-origin-when-cross-origin");
    }

    /**
     * Réponse de succès
     */
    public static function success($data = null, $message = 'OK', $statusCode = 200) {
        self::setCorsHeaders();
        http_response_code($statusCode);

        $response = [
            'success' => true,
            'message' => $message,
            'timestamp' => date('c'), // ISO 8601
            'version' => '1.0.0'
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Réponse d'erreur
     */
    public static function error($message = 'Erreur', $statusCode = 400, $errors = []) {
        self::setCorsHeaders();
        http_response_code($statusCode);

        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => date('c'),
            'version' => '1.0.0'
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        // En mode debug, ajouter plus d'informations
        $config = require __DIR__ . '/../Config/config.php';
        if ($config['app']['debug'] && $statusCode >= 500) {
            $response['debug'] = [
                'file' => debug_backtrace()[0]['file'] ?? 'unknown',
                'line' => debug_backtrace()[0]['line'] ?? 'unknown'
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Erreur 404 - Ressource non trouvée
     */
    public static function notFound($message = 'Ressource non trouvée') {
        self::error($message, 404);
    }

    /**
     * Erreur 500 - Erreur interne du serveur
     */
    public static function serverError($message = 'Erreur interne du serveur') {
        self::error($message, 500);
    }

    /**
     * Erreur 429 - Trop de requêtes
     */
    public static function tooManyRequests($message = 'Trop de requêtes. Veuillez réessayer plus tard.') {
        self::error($message, 429);
    }

    /**
     * Erreur 401 - Non autorisé
     */
    public static function unauthorized($message = 'Non autorisé') {
        self::error($message, 401);
    }

    /**
     * Erreur 403 - Interdit
     */
    public static function forbidden($message = 'Accès interdit') {
        self::error($message, 403);
    }

    /**
     * Erreur de validation
     */
    public static function validationError($errors = []) {
        self::error('Erreur de validation', 422, $errors);
    }

    /**
     * Réponse pour les requêtes OPTIONS (CORS preflight)
     */
    public static function handleOptions() {
        self::setCorsHeaders();
        http_response_code(200);
        exit;
    }

    /**
     * Vérifier si la requête est une requête CORS preflight
     */
    public static function isPreflightRequest() {
        return $_SERVER['REQUEST_METHOD'] === 'OPTIONS';
    }

    /**
     * Valider les headers CORS
     */
    public static function validateCors() {
        $config = require __DIR__ . '/../Config/config.php';

        // Vérifier l'origine si elle est présente
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $allowedOrigins = $config['cors']['allowed_origins'];

            if (!in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins) &&
                !in_array('*', $allowedOrigins)) {
                self::forbidden('Origine non autorisée');
            }
        }

        // Vérifier la méthode HTTP
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            $requestedMethod = $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'];
            $allowedMethods = $config['cors']['allowed_methods'];

            if (!in_array($requestedMethod, $allowedMethods)) {
                self::forbidden('Méthode HTTP non autorisée');
            }
        }
    }

    /**
     * Logger les erreurs API
     */
    private static function logError($message, $statusCode, $context = []) {
        $config = require __DIR__ . '/../Config/config.php';

        if ($config['logs']['enabled']) {
            $logFile = $config['logs']['directory'] . '/api_errors.log';
            $timestamp = date('Y-m-d H:i:s');
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $method = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
            $uri = $_SERVER['REQUEST_URI'] ?? 'unknown';

            $logMessage = sprintf(
                "[%s] %s - IP: %s - Method: %s - URI: %s - Status: %d - Context: %s\n",
                $timestamp,
                $message,
                $ip,
                $method,
                $uri,
                $statusCode,
                json_encode($context)
            );

            // Créer le dossier logs s'il n'existe pas
            if (!is_dir($config['logs']['directory'])) {
                mkdir($config['logs']['directory'], 0755, true);
            }

            file_put_contents($logFile, $logMessage, FILE_APPEND);
        }
    }
}