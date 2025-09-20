<?php
/**
 * Classe Validator - Validation des données d'entrée
 * Valide les paramètres des requêtes API
 */

class Validator {
    private $errors = [];
    private $config;

    public function __construct() {
        $this->config = require __DIR__ . '/../Config/config.php';
    }

    /**
     * Valider les paramètres de génération de texte
     */
    public function validateGenerateText($data) {
        $this->errors = [];

        // Validation du domaine
        if (empty($data['domain'])) {
            $this->errors['domain'] = 'Le domaine est obligatoire';
        } elseif (!is_string($data['domain'])) {
            $this->errors['domain'] = 'Le domaine doit être une chaîne de caractères';
        } elseif (!array_key_exists($data['domain'], $this->config['domains'])) {
            $this->errors['domain'] = 'Domaine non valide';
        }

        // Validation du sujet
        if (empty($data['topic'])) {
            $this->errors['topic'] = 'Le sujet est obligatoire';
        } elseif (!is_string($data['topic'])) {
            $this->errors['topic'] = 'Le sujet doit être une chaîne de caractères';
        } elseif (!empty($data['domain']) && !in_array($data['topic'], $this->config['domains'][$data['domain']] ?? [])) {
            $this->errors['topic'] = 'Sujet non valide pour ce domaine';
        }

        // Validation du niveau
        $validLevels = ['beginner', 'intermediate', 'advanced'];
        $validNumericLevels = [1, 2, 3, 4, 5];
        if (empty($data['level'])) {
            $this->errors['level'] = 'Le niveau est obligatoire';
        } elseif (!in_array($data['level'], $validLevels) && !in_array((int)$data['level'], $validNumericLevels)) {
            $this->errors['level'] = 'Niveau non valide. Valeurs acceptées: ' . implode(', ', $validLevels) . ' ou 1-5';
        }

        // Validation du ton
        $validTones = ['informative', 'formal', 'casual', 'enthusiastic', 'professional', 'educational', 'conversational', 'technical'];
        if (empty($data['tone'])) {
            $this->errors['tone'] = 'Le ton est obligatoire';
        } elseif (!in_array($data['tone'], $validTones)) {
            $this->errors['tone'] = 'Ton non valide. Valeurs acceptées: ' . implode(', ', $validTones);
        }

        // Validation de la longueur
        $validLengths = ['short', 'medium', 'long'];
        if (empty($data['length'])) {
            $this->errors['length'] = 'La longueur est obligatoire';
        } elseif (!in_array($data['length'], $validLengths)) {
            $this->errors['length'] = 'Longueur non valide. Valeurs acceptées: ' . implode(', ', $validLengths);
        }

        // Validation des options booléennes (optionnelles)
        $booleanFields = ['includeExamples', 'includeQuestions'];
        foreach ($booleanFields as $field) {
            if (isset($data[$field]) && !is_bool($data[$field])) {
                $this->errors[$field] = "Le champ {$field} doit être un booléen";
            }
        }

        // Définir les valeurs par défaut pour les champs optionnels
        if (!isset($data['includeExamples'])) {
            $data['includeExamples'] = false;
        }
        if (!isset($data['includeQuestions'])) {
            $data['includeQuestions'] = false;
        }

        return empty($this->errors);
    }

    /**
     * Valider les paramètres de traduction
     */
    public function validateTranslation($data) {
        $this->errors = [];

        if (empty($data['text'])) {
            $this->errors['text'] = 'Le texte à traduire est obligatoire';
        } elseif (!is_string($data['text'])) {
            $this->errors['text'] = 'Le texte doit être une chaîne de caractères';
        } elseif (strlen($data['text']) > 10000) {
            $this->errors['text'] = 'Le texte ne peut pas dépasser 10 000 caractères';
        } elseif (strlen($data['text']) < 10) {
            $this->errors['text'] = 'Le texte doit contenir au moins 10 caractères';
        }

        return empty($this->errors);
    }

    /**
     * Valider l'ID d'un domaine
     */
    public function validateDomainId($domainId) {
        $this->errors = [];

        if (!is_numeric($domainId)) {
            $this->errors['domainId'] = 'L\'ID du domaine doit être un nombre';
        } elseif ($domainId <= 0) {
            $this->errors['domainId'] = 'L\'ID du domaine doit être positif';
        }

        return empty($this->errors);
    }

    /**
     * Valider l'ID d'un texte généré
     */
    public function validateTextId($textId) {
        $this->errors = [];

        if (!is_numeric($textId)) {
            $this->errors['textId'] = 'L\'ID du texte doit être un nombre';
        } elseif ($textId <= 0) {
            $this->errors['textId'] = 'L\'ID du texte doit être positif';
        }

        return empty($this->errors);
    }

    /**
     * Valider l'ID de session
     */
    public function validateSessionId($sessionId) {
        $this->errors = [];

        if (empty($sessionId)) {
            $this->errors['sessionId'] = 'L\'ID de session est obligatoire';
        } elseif (!is_string($sessionId)) {
            $this->errors['sessionId'] = 'L\'ID de session doit être une chaîne de caractères';
        } elseif (strlen($sessionId) > 255) {
            $this->errors['sessionId'] = 'L\'ID de session ne peut pas dépasser 255 caractères';
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $sessionId)) {
            $this->errors['sessionId'] = 'L\'ID de session contient des caractères non valides';
        }

        return empty($this->errors);
    }

    /**
     * Nettoyer et valider les données d'entrée
     */
    public function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeInput'], $data);
        }

        if (is_string($data)) {
            // Supprimer les espaces inutiles
            $data = trim($data);
            // Échapper les caractères HTML
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            // Supprimer les caractères de contrôle
            $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        return $data;
    }

    /**
     * Valider les données JSON reçues
     */
    public function validateJsonInput() {
        // Vérifier le Content-Type
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') === false) {
            $this->errors['contentType'] = 'Content-Type doit être application/json';
            return false;
        }

        // Récupérer le corps de la requête
        $input = file_get_contents('php://input');
        if (empty($input)) {
            $this->errors['body'] = 'Corps de la requête vide';
            return false;
        }

        // Décoder le JSON
        $data = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->errors['json'] = 'JSON invalide: ' . json_last_error_msg();
            return false;
        }

        return $data;
    }

    /**
     * Vérifier s'il y a des erreurs
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * Obtenir les erreurs
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Obtenir la première erreur
     */
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }

    /**
     * Réinitialiser les erreurs
     */
    public function reset() {
        $this->errors = [];
    }

    /**
     * Valider la longueur d'une chaîne
     */
    public function validateLength($value, $field, $min = null, $max = null) {
        $length = strlen($value);

        if ($min !== null && $length < $min) {
            $this->errors[$field] = "Le champ {$field} doit contenir au moins {$min} caractères";
        }

        if ($max !== null && $length > $max) {
            $this->errors[$field] = "Le champ {$field} ne peut pas dépasser {$max} caractères";
        }
    }

    /**
     * Valider un email
     */
    public function validateEmail($email, $field = 'email') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Adresse email invalide';
        }
    }

    /**
     * Valider une URL
     */
    public function validateUrl($url, $field = 'url') {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->errors[$field] = 'URL invalide';
        }
    }
}