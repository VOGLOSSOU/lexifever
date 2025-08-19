# 🚀 Lexifever - Enrichissez votre vocabulaire anglais

Lexifever est une application web innovante qui génère des textes personnalisés en anglais avec traduction française pour enrichir votre vocabulaire de manière ciblée et efficace.

## ✨ Fonctionnalités

- **Sélection de domaine** : Choisissez parmi 9 domaines (Technologie, Sciences, Voyages, Business, Santé, etc.)
- **Sélection de sujet** : Sujets spécialisés dans chaque domaine
- **Personnalisation avancée** :
  - Niveau de difficulté (Débutant, Intermédiaire, Avancé)
  - Tonalité (Informatif, Conversationnel, Formel, Créatif, Technique)
  - Longueur du texte (Court, Moyen, Long)
  - Options supplémentaires (exemples, questions)
- **Génération IA** : Textes générés par Together AI (Llama 3.2)
- **Traduction automatique** : Traduction française instantanée
- **Interface moderne** : Design responsive avec Tailwind CSS

## 🛠️ Technologies utilisées

### Frontend
- HTML5, CSS3, JavaScript (Vanilla)
- Tailwind CSS pour le design
- Font Awesome pour les icônes

### Backend
- Node.js avec Express
- Together AI API (Llama 3.2-11B-Vision-Instruct-Turbo)
- Sécurisation avec Helmet et CORS
- Rate limiting

### Sécurité
- Variables d'environnement pour les clés API
- Rate limiting (100 requêtes/15min)
- Validation des paramètres
- Gestion d'erreurs robuste

## 📦 Installation

### Architecture Séparée Frontend/Backend

Le projet est maintenant organisé en deux parties distinctes :
- **`backend/`** : API Node.js/Express
- **`frontend/`** : Interface utilisateur HTML/JS

### Installation Rapide (Recommandée)

1. **Cloner le projet**
```bash
git clone <votre-repo>
cd lexifever
```

2. **Démarrage automatique**
```bash
./start.sh
```
Ce script configure automatiquement le backend et démarre l'application complète.

### Installation Manuelle

#### Backend
```bash
cd backend
npm install
cp .env.example .env
# Éditer .env avec votre clé API Together AI
npm start
```

#### Frontend (Développement séparé)
```bash
cd frontend
npm install
npm run dev
```

### Accès à l'application
- **Application complète** : http://localhost:3000 (backend sert le frontend)
- **Frontend seul** : http://localhost:8080 (développement)
- **API Backend** : http://localhost:3000/api/

## 🎯 Utilisation

### Flux utilisateur
1. **Page d'accueil** (`index.html`) - Présentation de Lexifever
2. **Sélection du domaine** (`select-domain.html`) - Choisir un domaine d'intérêt
3. **Sélection du sujet** (`select-topic.html`) - Choisir un sujet spécifique
4. **Personnalisation** (`customize-text.html`) - Configurer les paramètres
5. **Résultat** (`result.html`) - Texte généré avec traduction

### API Endpoints

#### `GET /api/health`
Vérification de l'état de l'API

#### `POST /api/generate-text`
Génération de texte personnalisé
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

#### `POST /api/translate`
Traduction de texte anglais vers français
```json
{
  "text": "Your English text here"
}
```

## 🧪 Tests

Une page de test est disponible à `/test.html` pour vérifier :
- Génération de texte
- Traduction
- Flux complet

## 📁 Structure du projet

```
lexifever/
├── 🎨 frontend/            # Interface utilisateur
│   ├── index.html          # Page d'accueil
│   ├── select-domain.html  # Sélection du domaine
│   ├── select-topic.html   # Sélection du sujet
│   ├── customize-text.html # Personnalisation
│   ├── result.html         # Affichage des résultats
│   ├── history.html        # Historique des textes
│   ├── test.html           # Page de test
│   ├── js/
│   │   └── app.js          # JavaScript principal
│   ├── package.json        # Dépendances frontend
│   └── README.md           # Documentation frontend
├── 🚀 backend/             # API et serveur
│   ├── server.js           # Serveur Express
│   ├── routes/
│   │   └── api.js          # Routes API
│   ├── package.json        # Dépendances backend
│   ├── .env                # Variables d'environnement
│   ├── ecosystem.config.js # Configuration PM2
│   └── README.md           # Documentation backend
├── start.sh                # Script de démarrage global
├── README.md               # Documentation principale
└── TECHNICAL.md            # Documentation technique
```

## 🔧 Configuration avancée

### Variables d'environnement
```bash
TOGETHER_API_KEY=your_api_key_here
PORT=3000
NODE_ENV=development
ALLOWED_ORIGINS=http://localhost:3000,http://127.0.0.1:5500
```

### Personnalisation des modèles
Vous pouvez modifier le modèle IA dans `routes/api.js` :
```javascript
model: "meta-llama/Llama-3.2-11B-Vision-Instruct-Turbo"
```

## 🚀 Déploiement

Pour déployer en production :

1. Configurer les variables d'environnement
2. Utiliser un gestionnaire de processus (PM2)
3. Configurer un reverse proxy (Nginx)
4. Activer HTTPS

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
- Signaler des bugs
- Proposer des améliorations
- Ajouter de nouveaux domaines/sujets
- Améliorer l'interface

## 📄 Licence

MIT License - Voir le fichier LICENSE pour plus de détails.

## 👨‍💻 Auteur

**VOGLOSSOU** - Développeur principal

---

🌟 **Lexifever** - Enrichissez votre vocabulaire anglais avec l'IA !
