<?php
/**
 * Point d'entrée principal de l'application Lexifever
 * Routeur PHP pour l'API REST
 */

// Activer l'affichage des erreurs en développement
$config = require __DIR__ . '/src/Config/config.php';
if ($config['app']['debug']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Définir le fuseau horaire
date_default_timezone_set($config['app']['timezone']);

// Headers de sécurité de base
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Gestion des requêtes OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Headers CORS pour les requêtes preflight
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        $allowedOrigins = $config['cors']['allowed_origins'];
        if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins) || in_array('*', $allowedOrigins)) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        }
    }

    header("Access-Control-Allow-Methods: " . implode(', ', $config['cors']['allowed_methods']));
    header("Access-Control-Allow-Headers: " . implode(', ', $config['cors']['allowed_headers']));
    header("Access-Control-Max-Age: " . $config['cors']['max_age']);
    exit;
}

// Inclure les classes nécessaires
require_once __DIR__ . '/src/Utils/ApiResponse.php';
require_once __DIR__ . '/src/Controllers/ApiController.php';

/**
 * Fonction de routage principale
 */
function routeRequest() {
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    // Nettoyer l'URI (supprimer les paramètres de requête)
    $path = parse_url($requestUri, PHP_URL_PATH);

    // Supprimer le préfixe du répertoire si nécessaire
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    if ($basePath !== '/' && strpos($path, $basePath) === 0) {
        $path = substr($path, strlen($basePath));
    }

    // Diviser le chemin
    $pathParts = explode('/', trim($path, '/'));

    // Initialiser le contrôleur API
    $apiController = new ApiController();

    try {
        // Routage des endpoints API
        if (isset($pathParts[0]) && $pathParts[0] === 'api') {
            return handleApiRoutes($pathParts, $requestMethod, $apiController);
        }

        // Si ce n'est pas une route API, servir les fichiers statiques du frontend
        return serveFrontendFiles($path);

    } catch (Exception $e) {
        error_log("Erreur de routage: " . $e->getMessage());
        ApiResponse::error('Erreur interne du serveur', 500);
    }
}

/**
 * Gestion des routes API
 */
function handleApiRoutes($pathParts, $requestMethod, $apiController) {
    // Vérifier qu'il y a au moins une partie après 'api'
    if (!isset($pathParts[1])) {
        ApiResponse::notFound('Endpoint API non trouvé');
        return;
    }

    $endpoint = $pathParts[1];
    $startTime = microtime(true);

    try {
        switch ($endpoint) {
            case 'health':
                if ($requestMethod === 'GET') {
                    $apiController->health();
                } else {
                    ApiResponse::error('Méthode non autorisée', 405);
                }
                break;

            case 'generate-text':
                if ($requestMethod === 'POST') {
                    $apiController->generateText();
                } else {
                    ApiResponse::error('Méthode non autorisée', 405);
                }
                break;

            case 'translate':
                if ($requestMethod === 'POST') {
                    $apiController->translate();
                } else {
                    ApiResponse::error('Méthode non autorisée', 405);
                }
                break;

            case 'domains':
                if ($requestMethod === 'GET') {
                    if (isset($pathParts[2]) && is_numeric($pathParts[2])) {
                        // Route: /api/domains/{id}/topics
                        if (isset($pathParts[3]) && $pathParts[3] === 'topics') {
                            $apiController->getTopics((int)$pathParts[2]);
                        } else {
                            ApiResponse::notFound('Endpoint non trouvé');
                        }
                    } else {
                        // Route: /api/domains
                        $apiController->getDomains();
                    }
                } else {
                    ApiResponse::error('Méthode non autorisée', 405);
                }
                break;

            case 'history':
                if ($requestMethod === 'GET') {
                    $apiController->getHistory();
                } elseif ($requestMethod === 'DELETE' && isset($pathParts[2]) && is_numeric($pathParts[2])) {
                    $apiController->deleteText((int)$pathParts[2]);
                } else {
                    ApiResponse::error('Méthode non autorisée', 405);
                }
                break;

            case 'stats':
                if ($requestMethod === 'GET') {
                    $apiController->getStats();
                } else {
                    ApiResponse::error('Méthode non autorisée', 405);
                }
                break;

            case 'search':
                if ($requestMethod === 'GET') {
                    $apiController->search();
                } else {
                    ApiResponse::error('Méthode non autorisée', 405);
                }
                break;

            default:
                ApiResponse::notFound('Endpoint API non trouvé');
                break;
        }

        // Logger la requête (si activé)
        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000, 2);
        logApiRequest($endpoint, $requestMethod, http_response_code(), $responseTime);

    } catch (Exception $e) {
        error_log("Erreur API: " . $e->getMessage());
        ApiResponse::error('Erreur interne du serveur', 500);
    }
}

/**
 * Servir les fichiers du frontend
 */
function serveFrontendFiles($path) {
    // Liste des fichiers autorisés
    $allowedFiles = [
        'index.html',
        'select-domain.html',
        'select-topic.html',
        'customize-text.html',
        'result.html',
        'history.html',
        'diagnostic.html',
        'footer.html',
        'header.html',
        'test-api.html',
        'test-customize.html',
        'test-flow.html',
        'test-full-flow.html',
        'test-result.html',
        'test-simple.html',
        'test.html'
    ];

    $allowedDirs = ['js'];

    // Chemin par défaut
    if (empty($path) || $path === '/' || $path === 'index.php') {
        $filePath = __DIR__ . '/index.html';
    } else {
        $filePath = __DIR__ . '/' . $path;
    }

    // Vérifier que le fichier existe et est autorisé
    if (file_exists($filePath) && is_file($filePath)) {
        $fileName = basename($filePath);
        $fileDir = dirname($filePath);

        // Vérifier les permissions
        if (in_array($fileName, $allowedFiles) ||
            (in_array(basename($fileDir), $allowedDirs) && pathinfo($fileName, PATHINFO_EXTENSION) === 'js')) {

            // Déterminer le type MIME
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $mimeTypes = [
                'html' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml'
            ];

            $mimeType = $mimeTypes[$extension] ?? 'text/plain';
            header("Content-Type: {$mimeType}");

            // Cache pour les ressources statiques
            if (in_array($extension, ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg'])) {
                $lastModified = filemtime($filePath);
                header("Last-Modified: " . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
                header("Cache-Control: public, max-age=3600"); // Cache 1 heure
            }

            readfile($filePath);
            return;
        }
    }

    // Fichier non trouvé ou non autorisé
    http_response_code(404);
    echo "<!DOCTYPE html>
    <html>
    <head><title>Page non trouvée</title></head>
    <body>
        <h1>404 - Page non trouvée</h1>
        <p>La page demandée n'existe pas.</p>
        <a href='/'>Retour à l'accueil</a>
    </body>
    </html>";
}

/**
 * Logger les requêtes API
 */
function logApiRequest($endpoint, $method, $statusCode, $responseTime) {
    $config = require __DIR__ . '/src/Config/config.php';

    if ($config['logs']['enabled']) {
        $logFile = $config['logs']['directory'] . '/api_requests.log';
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $logMessage = sprintf(
            "[%s] %s %s - Status: %d - Time: %.2fms - IP: %s - UA: %s\n",
            $timestamp,
            $method,
            $endpoint,
            $statusCode,
            $responseTime,
            $ip,
            substr($userAgent, 0, 100) // Limiter la longueur
        );

        // Créer le dossier logs s'il n'existe pas
        if (!is_dir($config['logs']['directory'])) {
            mkdir($config['logs']['directory'], 0755, true);
        }

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

// Traiter la requête
routeRequest();