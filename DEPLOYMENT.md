# 🚀 GUIDE DE DÉPLOIEMENT - Lexifever PHP

## 📋 Prérequis

### Système requis
- **PHP 8.1+** avec extensions :
  - `pdo_mysql` (connexion MySQL)
  - `curl` (appels API)
  - `json` (traitement JSON)
  - `mbstring` (encodage UTF-8)
- **MySQL 5.7+** ou **MariaDB 10.0+**
- **Apache/Nginx** avec `mod_rewrite`
- **SSL** recommandé pour la production

### Vérification des prérequis
```bash
# Vérifier PHP
php --version

# Vérifier les extensions
php -m | grep -E "(pdo_mysql|curl|json|mbstring)"

# Vérifier MySQL
mysql --version
```

---

## 🗄️ CONFIGURATION BASE DE DONNÉES

### 1. Créer la base de données
```sql
-- Se connecter à MySQL
mysql -u root -p

-- Créer la base de données
CREATE DATABASE lexifever CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Créer l'utilisateur (optionnel)
CREATE USER 'lexifever_user'@'localhost' IDENTIFIED BY 'mot_de_passe_sécurisé';
GRANT ALL PRIVILEGES ON lexifever.* TO 'lexifever_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Configuration dans `src/Config/config.php`
```php
'database' => [
    'host' => 'localhost',           // ou srv1580.hstgr.io pour Hostinger
    'name' => 'lexifever',           // ou u433704782_lexifever_data
    'user' => 'root',                // ou u433704782_myLexi_db_use
    'pass' => '',                    // ou mylExiF3werMdp1607@
    'charset' => 'utf8mb4',
    // ... autres options
]
```

---

## 🔑 CONFIGURATION API GEMINI

### 1. Obtenir la clé API
1. Aller sur [Google AI Studio](https://aistudio.google.com/apikey)
2. Créer un nouveau projet ou en sélectionner un existant
3. Générer une nouvelle clé API
4. Copier la clé

### 2. Configuration dans `src/Config/config.php`
```php
'gemini' => [
    'api_key' => 'AIzaSyB-s8lbOkQdHBge6ZiHnn2vXXIb-YjfkAA', // Votre clé API
    'base_url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent',
    // ... autres options
]
```

---

## 📁 DÉPLOIEMENT DES FICHIERS

### Structure finale sur le serveur
```
/public_html/ (ou /www/)
├── index.php              # Point d'entrée principal
├── .htaccess              # Configuration Apache
├── test.php               # Script de test (à supprimer après)
├── *.html                 # Pages frontend
├── js/                    # JavaScript
├── src/                   # Code PHP (protégé)
│   ├── Config/
│   ├── Controllers/
│   ├── Models/
│   ├── Services/
│   └── Utils/
├── logs/                  # Logs (auto-créé)
├── cache/                 # Cache (auto-créé)
└── composer.json          # Dépendances (optionnel)
```

### Commandes de déploiement
```bash
# Upload des fichiers
scp -r lexifever/* user@serveur:/path/to/public_html/

# Créer les dossiers nécessaires
mkdir -p logs cache
chmod 755 logs cache
chmod 644 *.php *.html
chmod 755 *.sh

# Protéger les fichiers sensibles
chmod 600 src/Config/config.php
```

---

## 🧪 TESTS ET VALIDATION

### 1. Test de base de données
Accéder à : `https://votredomaine.com/test.php`

Le script de test vérifiera :
- ✅ Connexion à la base de données
- ✅ Création des tables
- ✅ API Google Gemini
- ✅ Modèles de données
- ✅ Endpoints API

### 2. Tests manuels des API

#### Test de santé
```bash
curl -X GET "https://votredomaine.com/api/health"
```

#### Test des domaines
```bash
curl -X GET "https://votredomaine.com/api/domains"
```

#### Test de génération de texte
```bash
curl -X POST "https://votredomaine.com/api/generate-text" \
  -H "Content-Type: application/json" \
  -d '{
    "domain": "Technologie",
    "topic": "Intelligence Artificielle",
    "level": "intermediate",
    "tone": "informative",
    "length": "medium",
    "session_id": "test_session_123"
  }'
```

#### Test de traduction
```bash
curl -X POST "https://votredomaine.com/api/translate" \
  -H "Content-Type: application/json" \
  -d '{
    "text": "Artificial Intelligence is transforming our world."
  }'
```

### 3. Test de l'interface utilisateur
1. Ouvrir `https://votredomaine.com/index.html`
2. Tester le flux complet :
   - Sélection domaine → sujets → personnalisation → génération
3. Vérifier la traduction et l'historique

---

## 🔧 CONFIGURATION SERVEUR

### Apache (.htaccess déjà configuré)
Le fichier `.htaccess` inclus gère :
- ✅ Réécriture d'URL
- ✅ Headers de sécurité
- ✅ Compression GZIP
- ✅ Cache des ressources
- ✅ Protection des fichiers sensibles

### Nginx (configuration alternative)
```nginx
server {
    listen 80;
    server_name votredomaine.com;
    root /var/www/html;
    index index.php;

    # Logs
    access_log /var/log/nginx/lexifever_access.log;
    error_log /var/log/nginx/lexifever_error.log;

    # Sécurité
    add_header X-Frame-Options DENY;
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";

    # PHP
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    # API routes
    location /api/ {
        try_files $uri $uri/ /index.php?$args;
    }

    # Static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Protection
    location ~ /(src|logs|cache)/ {
        deny all;
        return 403;
    }
}
```

---

## 📊 MONITORING ET MAINTENANCE

### Logs à surveiller
- `logs/api_requests.log` - Requêtes API
- `logs/api_errors.log` - Erreurs API
- `logs/database.log` - Logs base de données
- `logs/cache.log` - Logs cache

### Métriques importantes
- **Temps de réponse API** : < 5 secondes
- **Taux de succès** : > 95%
- **Utilisation cache** : > 70%
- **Erreurs 5xx** : < 1%

### Tâches de maintenance
```bash
# Nettoyer les anciens textes (90 jours)
# Via script PHP ou cron job

# Optimiser le cache
# Via endpoint /api/cache/optimize

# Rotation des logs
# Configuration serveur automatique
```

---

## 🚨 DÉPANNAGE

### Problèmes courants

#### Erreur de connexion base de données
```
Erreur: SQLSTATE[HY000] [2002] Connection refused
```
**Solution** :
- Vérifier les identifiants dans `config.php`
- Vérifier que MySQL est démarré
- Vérifier les permissions utilisateur

#### Erreur API Gemini
```
Erreur: API Gemini error: 400 Bad Request
```
**Solution** :
- Vérifier la clé API
- Vérifier le quota Google AI Studio
- Vérifier la connectivité réseau

#### Erreur 500 Internal Server Error
**Solution** :
- Vérifier les logs PHP (`logs/api_errors.log`)
- Vérifier les permissions fichiers (755 pour dossiers, 644 pour fichiers)
- Vérifier la configuration PHP

#### Problème de cache
**Solution** :
- Vider le cache : `rm -rf cache/*`
- Vérifier les permissions du dossier cache
- Redémarrer le serveur web

---

## 🔒 SÉCURITÉ EN PRODUCTION

### Actions recommandées
1. **Désactiver le debug** : `'debug' => false` dans config.php
2. **Restreindre CORS** : Configurer les origines autorisées spécifiques
3. **Utiliser HTTPS** : Certificat SSL obligatoire
4. **Mettre à jour PHP** : Version récente et sécurisée
5. **Surveiller les logs** : Monitoring automatique des erreurs
6. **Sauvegarde régulière** : Base de données et fichiers

### Variables sensibles
- Clé API Gemini : Ne jamais committer en dur
- Identifiants base de données : Utiliser des variables d'environnement
- Fichiers de logs : Ne pas exposer publiquement

---

## 📞 SUPPORT ET MAINTENANCE

### Points de contact
- **Logs applicatifs** : `logs/` pour diagnostiquer les problèmes
- **Base de données** : Vérifier les tables et les données
- **API Gemini** : Console Google AI Studio pour les quotas

### Mises à jour
1. **Sauvegarde** avant toute mise à jour
2. **Test en développement** avant production
3. **Migration de base de données** si nécessaire
4. **Mise à jour progressive** des utilisateurs

---

## ✅ CHECKLIST DE DÉPLOIEMENT

- [ ] Serveur configuré (PHP 8.1+, MySQL, Apache/Nginx)
- [ ] Base de données créée et configurée
- [ ] Clé API Gemini obtenue et configurée
- [ ] Fichiers uploadés avec bonnes permissions
- [ ] Test script passé avec succès
- [ ] API endpoints testés manuellement
- [ ] Interface utilisateur fonctionnelle
- [ ] HTTPS configuré
- [ ] Monitoring et logs opérationnels
- [ ] Sauvegarde automatique configurée

---

*Guide de déploiement créé le 20 janvier 2025 - Version 1.0*