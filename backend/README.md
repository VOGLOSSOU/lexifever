# 🚀 Lexifever Backend

API backend de l'application Lexifever pour la génération de textes personnalisés et la traduction.

## 📁 Structure

```
backend/
├── server.js              # Serveur Express principal
├── routes/
│   └── api.js             # Routes API
├── logs/                  # Fichiers de logs
├── package.json           # Dépendances Node.js
├── .env                   # Variables d'environnement
├── .env.example           # Exemple de configuration
└── ecosystem.config.js    # Configuration PM2
```

## 🚀 Démarrage Rapide

### Prérequis
- Node.js 16+ 
- Clé API Together AI

### Installation
```bash
cd backend
npm install
```

### Configuration
```bash
# Copier le fichier d'exemple
cp .env.example .env

# Éditer le fichier .env avec votre clé API
TOGETHER_API_KEY=votre_cle_api_together_ai
```

### Démarrage
```bash
# Développement
npm start

# Production avec PM2
npm run prod
```

L'API sera accessible sur http://localhost:3000

## 🔧 Configuration

### Variables d'Environnement
```bash
# API Together AI
TOGETHER_API_KEY=your_together_ai_api_key_here

# Serveur
PORT=3000
NODE_ENV=development

# CORS
ALLOWED_ORIGINS=http://localhost:8080,http://127.0.0.1:8080
```

### Obtenir une Clé API Together AI
1. Allez sur https://api.together.xyz/
2. Créez un compte ou connectez-vous
3. Générez une clé API
4. Ajoutez-la dans votre fichier `.env`

## 📡 API Endpoints

### GET /api/health
Vérification de l'état de l'API

**Réponse :**
```json
{
  "status": "OK",
  "message": "API Lexifever fonctionnelle",
  "timestamp": "2025-07-09T15:42:26.183Z",
  "apiKeyStatus": "Définie"
}
```

### POST /api/generate-text
Génération de texte personnalisé

**Paramètres :**
```json
{
  "domain": "Technologie",
  "topic": "Intelligence Artificielle",
  "level": "intermediate",
  "tone": "informative",
  "length": "medium",
  "includeExamples": true,
  "includeQuestions": false
}
```

**Réponse :**
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
Traduction de texte anglais vers français

**Paramètres :**
```json
{
  "text": "Text to translate",
  "targetLanguage": "french"
}
```

**Réponse :**
```json
{
  "success": true,
  "data": {
    "originalText": "Text to translate",
    "translatedText": "Texte à traduire",
    "targetLanguage": "french",
    "timestamp": "2025-07-09T15:42:26.183Z"
  }
}
```

## 🔒 Sécurité

### Mesures Implémentées
- **Helmet** : Headers de sécurité HTTP
- **CORS** : Contrôle des origines autorisées
- **Rate Limiting** : 100 requêtes/15min par IP
- **Validation** : Validation des paramètres d'entrée
- **Variables d'environnement** : Clés API sécurisées

### Configuration CORS
```javascript
const corsOptions = {
    origin: process.env.ALLOWED_ORIGINS.split(','),
    credentials: true,
    optionsSuccessStatus: 200
};
```

## 🧪 Tests

### Tests API avec curl
```bash
# Test de santé
curl -X GET http://localhost:3000/api/health

# Test de génération
curl -X POST http://localhost:3000/api/generate-text \
  -H "Content-Type: application/json" \
  -d '{"domain":"Technologie","topic":"IA","level":"intermediate","tone":"informative","length":"medium"}'

# Test de traduction
curl -X POST http://localhost:3000/api/translate \
  -H "Content-Type: application/json" \
  -d '{"text":"Hello world"}'
```

### Tests Automatisés
```bash
# À implémenter
npm test
```

## 📊 Monitoring

### Logs
- **Développement** : Console
- **Production** : Fichiers dans `/logs/`
  - `err.log` : Erreurs
  - `out.log` : Sorties
  - `combined.log` : Combiné

### Métriques PM2
```bash
pm2 monit
pm2 logs lexifever
pm2 restart lexifever
```

## 🚀 Déploiement

### Développement
```bash
npm start
```

### Production avec PM2
```bash
# Installation PM2
npm install -g pm2

# Démarrage
pm2 start ecosystem.config.js --env production

# Sauvegarde de la configuration
pm2 save
pm2 startup
```

### Docker (Optionnel)
```dockerfile
FROM node:16-alpine
WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production
COPY . .
EXPOSE 3000
CMD ["npm", "start"]
```

## ⚡ Performance

### Optimisations
- **Clustering** : Support multi-processus
- **Compression** : Gzip automatique
- **Caching** : Headers appropriés
- **Rate Limiting** : Protection contre les abus

### Monitoring
- Temps de réponse API
- Utilisation mémoire
- Taux d'erreur
- Nombre de requêtes

## 🔧 Maintenance

### Mise à Jour
```bash
npm audit
npm update
```

### Sauvegarde
- Variables d'environnement
- Logs (rotation automatique)
- Configuration PM2

### Surveillance
- Monitoring des APIs externes (Together AI)
- Alertes en cas d'erreur
- Métriques de performance

## 🤝 Contribution

Pour contribuer au backend :
1. Respectez la structure des routes
2. Ajoutez la validation des paramètres
3. Gérez les erreurs proprement
4. Documentez les nouveaux endpoints
5. Testez avec curl ou Postman

## 📞 Support

Pour les problèmes backend :
1. Vérifiez les logs : `pm2 logs lexifever`
2. Testez les endpoints avec curl
3. Vérifiez la configuration `.env`
4. Consultez la documentation Together AI
