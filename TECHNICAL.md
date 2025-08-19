# 🔧 Documentation Technique - Lexifever

## Architecture du Projet

### Structure des Fichiers
```
lexifever/
├── 📄 Pages HTML
│   ├── index.html              # Page d'accueil
│   ├── select-domain.html      # Sélection du domaine
│   ├── select-topic.html       # Sélection du sujet
│   ├── customize-text.html     # Personnalisation
│   ├── result.html            # Affichage des résultats
│   ├── history.html           # Historique des textes
│   ├── header.html            # Header réutilisable
│   ├── footer.html            # Footer réutilisable
│   └── test.html              # Page de test
├── 🎨 Assets
│   └── js/
│       └── app.js             # JavaScript principal
├── 🚀 Backend
│   ├── server.js              # Serveur Express
│   └── routes/
│       └── api.js             # Routes API
├── ⚙️ Configuration
│   ├── package.json           # Dépendances
│   ├── .env                   # Variables d'environnement
│   ├── .env.example           # Exemple de configuration
│   ├── .gitignore             # Fichiers ignorés par Git
│   ├── ecosystem.config.js    # Configuration PM2
│   └── start.sh               # Script de démarrage
└── 📚 Documentation
    ├── README.md              # Documentation utilisateur
    └── TECHNICAL.md           # Documentation technique
```

## Technologies Utilisées

### Frontend
- **HTML5** : Structure sémantique
- **Tailwind CSS** : Framework CSS utilitaire
- **JavaScript Vanilla** : Logique côté client
- **Font Awesome** : Icônes
- **LocalStorage** : Persistance côté client

### Backend
- **Node.js** : Runtime JavaScript
- **Express.js** : Framework web
- **Together AI API** : Génération de texte IA
- **Helmet** : Sécurité HTTP
- **CORS** : Gestion des requêtes cross-origin
- **Express Rate Limit** : Limitation du taux de requêtes

## API Endpoints

### GET /api/health
**Description** : Vérification de l'état de l'API

**Réponse** :
```json
{
  "status": "OK",
  "message": "API Lexifever fonctionnelle",
  "timestamp": "2025-07-09T15:42:26.183Z",
  "apiKeyStatus": "Définie"
}
```

### POST /api/generate-text
**Description** : Génération de texte personnalisé

**Paramètres** :
```json
{
  "domain": "string",        // Domaine (ex: "Technologie")
  "topic": "string",         // Sujet (ex: "Intelligence Artificielle")
  "level": "string",         // Niveau: "beginner", "intermediate", "advanced"
  "tone": "string",          // Tonalité: "informative", "conversational", "formal", "creative", "technical"
  "length": "string",        // Longueur: "short", "medium", "long"
  "includeExamples": boolean, // Inclure des exemples (optionnel)
  "includeQuestions": boolean // Inclure des questions (optionnel)
}
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "englishText": "Generated text content...",
    "parameters": { /* paramètres utilisés */ },
    "timestamp": "2025-07-09T15:42:26.183Z"
  }
}
```

### POST /api/translate
**Description** : Traduction de texte anglais vers français

**Paramètres** :
```json
{
  "text": "string",           // Texte à traduire
  "targetLanguage": "french" // Langue cible (optionnel, défaut: "french")
}
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "originalText": "Original English text",
    "translatedText": "Texte français traduit",
    "targetLanguage": "french",
    "timestamp": "2025-07-09T15:42:26.183Z"
  }
}
```

## Flux de Données

### 1. Sélection du Domaine
- L'utilisateur clique sur un domaine dans `select-domain.html`
- Le domaine est passé en paramètre URL vers `select-topic.html`

### 2. Sélection du Sujet
- L'utilisateur clique sur un sujet dans `select-topic.html`
- Les paramètres (domaine + sujet) sont sauvegardés dans `localStorage`
- Redirection vers `customize-text.html`

### 3. Personnalisation
- L'utilisateur configure les paramètres dans `customize-text.html`
- Tous les paramètres sont sauvegardés dans `localStorage`
- Redirection vers `result.html`

### 4. Génération et Affichage
- `result.html` récupère les paramètres depuis `localStorage`
- Appel API pour générer le texte anglais
- Appel API pour traduire en français
- Affichage des deux textes
- Sauvegarde automatique dans l'historique

## Gestion des Données

### SessionManager
```javascript
// Sauvegarder les paramètres
SessionManager.saveParams(params);

// Récupérer les paramètres
const params = SessionManager.getParams();

// Nettoyer la session
SessionManager.clear();
```

### HistoryManager
```javascript
// Sauvegarder un texte
const textId = HistoryManager.saveText(textData);

// Récupérer l'historique
const history = HistoryManager.getHistory();

// Supprimer un texte
HistoryManager.deleteText(id);

// Vider l'historique
HistoryManager.clearHistory();
```

## Sécurité

### Variables d'Environnement
- `TOGETHER_API_KEY` : Clé API Together AI (sensible)
- `PORT` : Port du serveur
- `NODE_ENV` : Environnement (development/production)
- `ALLOWED_ORIGINS` : Origines autorisées pour CORS

### Mesures de Sécurité
- **Helmet** : Headers de sécurité HTTP
- **CORS** : Contrôle des origines autorisées
- **Rate Limiting** : 100 requêtes/15min par IP
- **Validation** : Validation des paramètres d'entrée
- **Gestion d'erreurs** : Messages d'erreur sécurisés

## Performance

### Optimisations Frontend
- **Lazy Loading** : Chargement différé des images
- **Animations CSS** : Transitions fluides
- **LocalStorage** : Cache côté client
- **Compression** : Minification automatique

### Optimisations Backend
- **Clustering** : Support multi-processus avec PM2
- **Caching** : Headers de cache appropriés
- **Compression** : Compression gzip
- **Monitoring** : Logs structurés

## Déploiement

### Développement
```bash
npm install
npm start
```

### Production avec PM2
```bash
npm install -g pm2
pm2 start ecosystem.config.js --env production
pm2 save
pm2 startup
```

### Variables d'Environnement Production
```bash
NODE_ENV=production
PORT=3000
TOGETHER_API_KEY=your_production_api_key
ALLOWED_ORIGINS=https://yourdomain.com
```

## Monitoring et Logs

### Logs PM2
- **Error Log** : `./logs/err.log`
- **Output Log** : `./logs/out.log`
- **Combined Log** : `./logs/combined.log`

### Métriques
- Temps de réponse API
- Taux d'erreur
- Utilisation mémoire
- Nombre de requêtes

## Tests

### Tests Manuels
- Page de test : `/test.html`
- Tests API : `curl` commands
- Tests d'intégration : Flux complet

### Tests Automatisés (À implémenter)
- Tests unitaires : Jest
- Tests d'intégration : Supertest
- Tests E2E : Playwright

## Maintenance

### Mise à Jour des Dépendances
```bash
npm audit
npm update
```

### Sauvegarde
- Base de données : LocalStorage (côté client)
- Configuration : Variables d'environnement
- Logs : Rotation automatique

### Surveillance
- Monitoring des APIs externes
- Alertes en cas d'erreur
- Métriques de performance
