<?php
/**
 * Configuration Lexifever
 * Fichier de configuration centralisée
 */

return [
    // Configuration base de données
    'database' => [
        'host' => 'srv1580.hstgr.io',
        'name' => 'u433704782_lexifever_data',
        'user' => 'u433704782_myLexi_db_use',
        'pass' => 'mylExiF3werMdp1607@',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],

    // Configuration API Google Gemini
    'gemini' => [
        'api_key' => 'AIzaSyB-s8lbOkQdHBge6ZiHnn2vXXIb-YjfkAA',
        'base_url' => 'https://generativelanguage.googleapis.com/v1beta/models/',
        'timeout' => 30, // secondes
        'max_retries' => 3,
        'cache_duration' => 3600, // 1 heure
    ],

    // Configuration application
    'app' => [
        'name' => 'Lexifever',
        'version' => '1.0.0',
        'debug' => getenv('APP_DEBUG') ?: true,
        'log_errors' => true,
        'timezone' => 'Europe/Paris',
        'session_lifetime' => 86400, // 24 heures
    ],

    // Configuration CORS
    'cors' => [
        'allowed_origins' => [
            'http://localhost:3000',
            'http://127.0.0.1:5500',
            'https://lexifever.com',
            '*' // À restreindre en production
        ],
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers' => [
            'Content-Type',
            'Authorization',
            'X-Requested-With',
            'Accept',
            'Origin'
        ],
        'max_age' => 86400, // 24 heures
    ],

    // Configuration rate limiting
    'rate_limit' => [
        'max_requests' => 100, // par fenêtre
        'window_minutes' => 15, // fenêtre en minutes
        'block_duration' => 60, // minutes de blocage
    ],

    // Configuration cache
    'cache' => [
        'enabled' => true,
        'default_ttl' => 3600, // 1 heure
        'directory' => __DIR__ . '/../../cache',
    ],

    // Configuration logs
    'logs' => [
        'enabled' => true,
        'directory' => __DIR__ . '/../../logs',
        'max_files' => 30,
        'max_file_size' => 10485760, // 10MB
    ],

    // Domaines et sujets par défaut
    'domains' => [
        'Technologie' => [
            'Intelligence Artificielle',
            'Développement Web',
            'Cybersécurité',
            'Blockchain',
            'Développement Mobile',
            'Cloud Computing'
        ],
        'Sciences' => [
            'Astronomie',
            'Biologie',
            'Physique',
            'Chimie'
        ],
        'Business' => [
            'Marketing Digital',
            'Entrepreneuriat',
            'Finance',
            'Management'
        ],
        'Santé' => [
            'Nutrition',
            'Fitness',
            'Médecine',
            'Bien-être Mental'
        ],
        'Arts' => [
            'Peinture',
            'Musique',
            'Littérature',
            'Cinéma'
        ],
        'Voyage' => [
            'Destinations Exotiques',
            'Aventure et Nature',
            'Voyage Urbain',
            'Conseils de Voyage'
        ],
        'Sport' => [
            'Football',
            'Basketball',
            'Tennis',
            'Sports Olympiques'
        ],
        'Histoire' => [
            'Histoire Ancienne',
            'Histoire Moderne',
            'Histoire Contemporaine',
            'Biographies'
        ],
        'Cuisine' => [
            'Cuisine Française',
            'Cuisine Internationale',
            'Pâtisserie',
            'Cuisine Saine'
        ]
    ]
];