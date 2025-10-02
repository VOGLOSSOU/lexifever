<?php
/**
 * Classe GeminiAIService - Service pour l'API Google Gemini
 * Gère la génération de texte et les traductions
 */

class GeminiAIService {
    private $apiKey;
    private $baseUrl;
    private $cache;
    private $config;

    public function __construct() {
        $this->config = require __DIR__ . '/../Config/config.php';
        $this->apiKey = $this->config['gemini']['api_key'];
        $this->baseUrl = $this->config['gemini']['base_url'];
        $this->cache = new Cache();
    }

    /**
     * Générer un texte personnalisé
     */
    public function generateText($params) {
        // Créer une clé de cache unique
        $cacheKey = $this->cache->generateKey($params);

        // Vérifier le cache d'abord
        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        // Mode fallback : retourner un texte d'exemple si l'API ne fonctionne pas
        try {
            // Construire le prompt
            $prompt = $this->buildGenerationPrompt($params);

            // Appeler l'API Gemini
            $response = $this->callGeminiApi($prompt);

            // Traiter la réponse
            $result = $this->processGenerationResponse($response, $params);
        } catch (Exception $e) {
            // Fallback : générer un texte d'exemple
            error_log("API Gemini indisponible, utilisation du mode fallback: " . $e->getMessage());
            $result = $this->generateFallbackText($params);
        }

        // Mettre en cache
        $this->cache->set($cacheKey, $result, $this->config['gemini']['cache_duration']);

        return $result;
    }

    /**
     * Traduire un texte
     */
    public function translateText($text, $targetLanguage = 'french') {
        // Créer une clé de cache unique
        $cacheKey = $this->cache->generateKey(['text' => $text, 'target' => $targetLanguage]);

        // Vérifier le cache d'abord
        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        // Construire le prompt de traduction
        $prompt = $this->buildTranslationPrompt($text, $targetLanguage);

        // Appeler l'API Gemini
        $response = $this->callGeminiApi($prompt);

        // Traiter la réponse
        $result = $this->processTranslationResponse($response, $text, $targetLanguage);

        // Mettre en cache
        $this->cache->set($cacheKey, $result, $this->config['gemini']['cache_duration']);

        return $result;
    }

    /**
     * Construire le prompt pour la génération de texte
     */
    private function buildGenerationPrompt($params) {
        $levelDescriptions = [
            'beginner' => 'débutant (niveau A1-A2), utilisant un vocabulaire simple et des phrases courtes',
            'intermediate' => 'intermédiaire (niveau B1-B2), utilisant un vocabulaire modéré et des structures de phrases variées',
            'advanced' => 'avancé (niveau C1-C2), utilisant un vocabulaire sophistiqué et des structures de phrases complexes'
        ];

        $lengthDescriptions = [
            'short' => '150-200 mots',
            'medium' => '300-400 mots',
            'long' => '500-600 mots'
        ];

        $toneDescriptions = [
            'informative' => 'informatif et factuel',
            'formal' => 'formel et professionnel',
            'professional' => 'professionnel et expert',
            'conversational' => 'conversationnel et amical',
            'enthusiastic' => 'enthousiaste et motivant',
            'educational' => 'éducatif et pédagogique',
            'technical' => 'technique et spécialisé'
        ];

        $prompt = "Écris un texte en anglais sur le sujet suivant :\n\n";
        $prompt .= "Domaine : {$params['domain']}\n";
        $prompt .= "Sujet : {$params['topic']}\n";
        $prompt .= "Niveau : {$levelDescriptions[$params['level']]}\n";
        $prompt .= "Tonalité : {$toneDescriptions[$params['tone']]}\n";
        $prompt .= "Longueur : approximativement {$lengthDescriptions[$params['length']]}\n\n";

        if (!empty($params['includeExamples'])) {
            $prompt .= "• Inclure des exemples pratiques pour illustrer les concepts\n";
        }

        if (!empty($params['includeQuestions'])) {
            $prompt .= "• Terminer par 2-3 questions de réflexion\n";
        }

        $prompt .= "\nFournis UNIQUEMENT le texte en anglais, sans titre ni formatage spécial.";

        return $prompt;
    }

    /**
     * Construire le prompt pour la traduction
     */
    private function buildTranslationPrompt($text, $targetLanguage) {
        $languageNames = [
            'french' => 'français',
            'spanish' => 'espagnol',
            'german' => 'allemand',
            'italian' => 'italien'
        ];

        $targetName = $languageNames[$targetLanguage] ?? $targetLanguage;

        $prompt = "Traduis le texte anglais suivant en {$targetName}. ";
        $prompt .= "Fournis UNIQUEMENT la traduction, sans explications ni commentaires :\n\n";
        $prompt .= $text;

        return $prompt;
    }

    /**
     * Appeler l'API Google Gemini
     */
    private function callGeminiApi($prompt, $model = 'gemini-1.5-flash-latest') {
        if (empty($this->apiKey)) {
            throw new Exception("Clé API Gemini non configurée");
        }

        $url = $this->baseUrl . $model . ':generateContent?key=' . $this->apiKey;

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => $this->getTemperatureForPrompt($prompt),
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => $this->getMaxTokensForPrompt($prompt)
            ]
        ];

        $jsonData = json_encode($data);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ],
            CURLOPT_TIMEOUT => $this->config['gemini']['timeout'],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Erreur cURL: " . $error);
        }

        if ($httpCode !== 200) {
            // Gérer les erreurs spécifiques
            $errorData = json_decode($response, true);
            if ($errorData && isset($errorData['error']['code'])) {
                $errorCode = $errorData['error']['code'];
                $errorMessage = $errorData['error']['message'] ?? 'Erreur inconnue';

                switch ($errorCode) {
                    case 429:
                        throw new Exception("Quota API dépassé. Veuillez réessayer dans quelques minutes. Détails: {$errorMessage}");
                    case 403:
                        throw new Exception("Accès refusé à l'API Gemini. Vérifiez votre clé API. Détails: {$errorMessage}");
                    case 400:
                        throw new Exception("Paramètres invalides pour l'API Gemini. Détails: {$errorMessage}");
                    case 500:
                    case 502:
                    case 503:
                        throw new Exception("Erreur temporaire du serveur Gemini. Veuillez réessayer. Détails: {$errorMessage}");
                    default:
                        throw new Exception("Erreur API Gemini (HTTP {$httpCode}): {$errorMessage}");
                }
            } else {
                throw new Exception("Erreur API Gemini (HTTP {$httpCode}): " . $response);
            }
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Log de debug pour voir la réponse brute
            error_log("Réponse brute de l'API Gemini: " . substr($response, 0, 500));
            throw new Exception("Erreur de décodage JSON: " . json_last_error_msg() . ". Réponse: " . substr($response, 0, 200));
        }

        return $result;
    }

    /**
     * Traiter la réponse de génération
     */
    private function processGenerationResponse($response, $params) {
        if (!isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            throw new Exception("Réponse API Gemini invalide");
        }

        $generatedText = trim($response['candidates'][0]['content']['parts'][0]['text']);

        return [
            'englishText' => $generatedText,
            'parameters' => $params,
            'timestamp' => date('c'),
            'model' => 'gemini-1.5-flash-latest'
        ];
    }

    /**
     * Traiter la réponse de traduction
     */
    private function processTranslationResponse($response, $originalText, $targetLanguage) {
        if (!isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            throw new Exception("Réponse API Gemini invalide");
        }

        $translatedText = trim($response['candidates'][0]['content']['parts'][0]['text']);

        return [
            'originalText' => $originalText,
            'translatedText' => $translatedText,
            'targetLanguage' => $targetLanguage,
            'timestamp' => date('c'),
            'model' => 'gemini-2.5-flash'
        ];
    }

    /**
     * Déterminer la température selon le type de prompt
     */
    private function getTemperatureForPrompt($prompt) {
        if (stripos($prompt, 'traduis') !== false) {
            return 0.1; // Très précis pour les traductions
        } elseif (stripos($prompt, 'informative') !== false || stripos($prompt, 'formal') !== false) {
            return 0.3; // Modérément créatif
        } elseif (stripos($prompt, 'enthusiastic') !== false || stripos($prompt, 'conversational') !== false) {
            return 0.7; // Plus créatif
        } else {
            return 0.5; // Valeur par défaut
        }
    }

    /**
     * Déterminer le nombre maximum de tokens selon le type de prompt
     */
    private function getMaxTokensForPrompt($prompt) {
        if (stripos($prompt, 'traduis') !== false) {
            return 1000; // Les traductions sont généralement plus courtes
        } elseif (stripos($prompt, 'short') !== false) {
            return 300;
        } elseif (stripos($prompt, 'medium') !== false) {
            return 600;
        } elseif (stripos($prompt, 'long') !== false) {
            return 900;
        } else {
            return 600; // Valeur par défaut
        }
    }

    /**
     * Vérifier la santé de l'API
     */
    public function healthCheck() {
        try {
            // Test simple avec un prompt minimal
            $testPrompt = "Say 'Hello' in English.";
            $response = $this->callGeminiApi($testPrompt);

            return [
                'status' => 'OK',
                'message' => 'API Gemini fonctionnelle',
                'timestamp' => date('c'),
                'apiKeyConfigured' => !empty($this->apiKey)
            ];
        } catch (Exception $e) {
            return [
                'status' => 'ERROR',
                'message' => 'API Gemini non fonctionnelle: ' . $e->getMessage(),
                'timestamp' => date('c'),
                'apiKeyConfigured' => !empty($this->apiKey)
            ];
        }
    }

    /**
     * Obtenir les statistiques d'utilisation
     */
    public function getStats() {
        // Cette méthode pourrait être étendue pour suivre l'utilisation
        return [
            'model' => 'gemini-1.5-flash-latest',
            'base_url' => $this->baseUrl,
            'cache_enabled' => $this->config['cache']['enabled'],
            'timeout' => $this->config['gemini']['timeout'],
            'max_retries' => $this->config['gemini']['max_retries']
        ];
    }

    /**
     * Générer un texte d'exemple en fallback
     */
    private function generateFallbackText($params) {
        $fallbackTexts = [
            'Technologie' => [
                'Intelligence Artificielle' => [
                    'beginner' => "Artificial Intelligence is a field of computer science that aims to create machines capable of intelligent behavior. Machine learning is a subset of AI that allows computers to learn from data without being explicitly programmed. Deep learning uses neural networks with multiple layers to process complex patterns in data.",
                    'intermediate' => "Artificial Intelligence encompasses various technologies including machine learning, natural language processing, and computer vision. Machine learning algorithms can be supervised, unsupervised, or reinforcement learning. Neural networks, inspired by the human brain, form the backbone of modern AI systems.",
                    'advanced' => "Contemporary artificial intelligence systems leverage transformer architectures and large language models to achieve unprecedented performance in natural language understanding. The convergence of AI with quantum computing promises revolutionary advances in computational capabilities, while ethical considerations around bias, privacy, and autonomous decision-making become increasingly critical."
                ],
                'Développement Web' => [
                    'beginner' => "Web development involves creating websites and web applications. HTML provides the structure, CSS handles the styling, and JavaScript adds interactivity. Modern web development often uses frameworks like React, Vue.js, or Angular to build dynamic user interfaces.",
                    'intermediate' => "Full-stack web development requires knowledge of both frontend and backend technologies. Frontend frameworks like React enable component-based development, while backend solutions such as Node.js, Python Django, or PHP Laravel handle server-side logic and database interactions.",
                    'advanced' => "Modern web architectures embrace microservices, serverless computing, and progressive web apps. Performance optimization, accessibility compliance, and security best practices are essential for production-grade applications. The JAMstack approach combines JavaScript, APIs, and markup for scalable, secure web experiences."
                ]
            ],
            'Sciences' => [
                'Astronomie' => [
                    'beginner' => "Astronomy is the scientific study of celestial objects and phenomena. Stars, planets, galaxies, and black holes are among the objects studied by astronomers. The universe is approximately 13.8 billion years old according to current scientific estimates.",
                    'intermediate' => "Our solar system consists of eight planets orbiting the Sun, with Earth being the third planet from the Sun. Galaxies contain billions of stars and are organized into clusters and superclusters. The Big Bang theory explains the origin and evolution of the universe.",
                    'advanced' => "Cosmological observations reveal that dark matter and dark energy constitute approximately 95% of the universe's mass-energy content. Gravitational waves, detected by LIGO, provide new insights into cosmic events. Exoplanet discovery techniques continue to expand our understanding of planetary systems beyond our own."
                ]
            ]
        ];

        // Sélectionner un texte approprié ou utiliser un texte générique
        $domain = $params['domain'] ?? 'Technologie';
        $topic = $params['topic'] ?? 'Intelligence Artificielle';
        $level = $params['level'] ?? 'intermediate';

        $text = $fallbackTexts[$domain][$topic][$level] ??
                $fallbackTexts['Technologie']['Intelligence Artificielle']['intermediate'];

        return [
            'englishText' => $text,
            'parameters' => $params,
            'timestamp' => date('c'),
            'model' => 'fallback-mode',
            'note' => 'Ce texte est généré en mode fallback car l\'API Gemini n\'est pas disponible.'
        ];
    }
}