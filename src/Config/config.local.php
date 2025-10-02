<?php
/**
 * Configuration locale pour les tests
 * À utiliser uniquement en développement local
 */

return [
    // Configuration base de données locale
    'database' => [
        'host' => 'localhost',
        'name' => 'lexifever_local',
        'user' => 'root',
        'pass' => '', // Mot de passe vide pour XAMPP par défaut
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],

    // Configuration API Google Gemini (même que production)
    'gemini' => [
        'api_key' => 'AIzaSyB-s8lbOkQdHBge6ZiHnn2vXXIb-YjfkAA',
        'base_url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent',
        'timeout' => 30,
        'max_retries' => 3,
        'cache_duration' => 3600,
    ],

    // Configuration application
    'app' => [
        'name' => 'Lexifever Local',
        'version' => '1.0.0',
        'debug' => true,
        'log_errors' => true,
        'timezone' => 'Europe/Paris',
        'session_lifetime' => 86400,
    ],

    // Configuration CORS pour développement local
    'cors' => [
        'allowed_origins' => [
            'http://localhost',
            'http://127.0.0.1',
            'http://localhost:3000',
            'http://127.0.0.1:5500',
            '*' // Permissif pour les tests locaux
        ],
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers' => [
            'Content-Type',
            'Authorization',
            'X-Requested-With',
            'Accept',
            'Origin'
        ],
        'max_age' => 86400,
    ],

    // Configuration rate limiting (désactivé pour les tests)
    'rate_limit' => [
        'max_requests' => 1000, // Très permissif pour les tests
        'window_minutes' => 15,
        'block_duration' => 1, // Blocage très court
    ],

    // Configuration cache
    'cache' => [
        'enabled' => true,
        'default_ttl' => 3600,
        'directory' => __DIR__ . '/../../cache',
    ],

    // Configuration logs
    'logs' => [
        'enabled' => true,
        'directory' => __DIR__ . '/../../logs',
        'max_files' => 30,
        'max_file_size' => 10485760,
    ],

    // Domaines et sujets (même configuration)
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