# 🎨 Lexifever Frontend

Interface utilisateur de l'application Lexifever pour l'enrichissement du vocabulaire anglais.

## 📁 Structure

```
frontend/
├── index.html              # Page d'accueil
├── select-domain.html      # Sélection du domaine
├── select-topic.html       # Sélection du sujet
├── customize-text.html     # Personnalisation des paramètres
├── result.html            # Affichage des résultats
├── history.html           # Historique des textes
├── header.html            # Header réutilisable
├── footer.html            # Footer réutilisable
├── test.html              # Page de test
└── js/
    └── app.js             # JavaScript principal
```

## 🚀 Démarrage Rapide

### Prérequis
- Node.js (pour les outils de développement)
- Backend Lexifever en cours d'exécution sur http://localhost:3000

### Installation
```bash
cd frontend
npm install
```

### Développement
```bash
# Serveur de développement avec rechargement automatique
npm run dev

# Ou serveur HTTP simple
npm start
```

L'application sera accessible sur http://localhost:8080

## 🔧 Configuration

### API Backend
Le frontend est configuré pour communiquer avec le backend sur :
- **URL par défaut** : `http://localhost:3000`
- **Configuration** : Dans `js/app.js`, variable `API_BASE_URL`

Pour changer l'URL du backend, modifiez cette ligne dans `js/app.js` :
```javascript
const API_BASE_URL = 'http://localhost:3000/api';
```

## 🎯 Fonctionnalités

### Pages Principales
1. **Accueil** (`index.html`) - Présentation de Lexifever
2. **Domaines** (`select-domain.html`) - Sélection du domaine d'intérêt
3. **Sujets** (`select-topic.html`) - Choix du sujet spécifique
4. **Personnalisation** (`customize-text.html`) - Configuration des paramètres
5. **Résultats** (`result.html`) - Affichage du texte généré et traduit
6. **Historique** (`history.html`) - Gestion des textes sauvegardés

### Composants JavaScript
- **SessionManager** : Gestion des paramètres de session
- **HistoryManager** : Gestion de l'historique des textes
- **ApiClient** : Communication avec le backend
- **UI** : Utilitaires d'interface utilisateur

## 🎨 Technologies

- **HTML5** : Structure sémantique
- **Tailwind CSS** : Framework CSS (via CDN)
- **JavaScript Vanilla** : Logique côté client
- **Font Awesome** : Icônes (via CDN)
- **LocalStorage** : Persistance des données côté client

## 📱 Responsive Design

L'interface est entièrement responsive et optimisée pour :
- 📱 Mobile (320px+)
- 📱 Tablette (768px+)
- 💻 Desktop (1024px+)

## 🔄 Flux Utilisateur

1. **Sélection** : Domaine → Sujet → Personnalisation
2. **Génération** : Appel API → Texte anglais → Traduction française
3. **Affichage** : Texte bilingue avec options d'interaction
4. **Sauvegarde** : Historique automatique dans localStorage

## 🧪 Tests

### Page de Test
Accédez à `/test.html` pour tester :
- Génération de texte
- Traduction
- Flux complet
- Gestion de l'historique

### Tests Manuels
1. Naviguez à travers toutes les pages
2. Testez la génération avec différents paramètres
3. Vérifiez la sauvegarde dans l'historique
4. Testez sur différents appareils/navigateurs

## 🚀 Déploiement

### Développement Local
```bash
npm run dev
```

### Production
Le frontend peut être déployé sur n'importe quel serveur web statique :
- Netlify
- Vercel
- GitHub Pages
- Apache/Nginx

**Important** : Assurez-vous de mettre à jour `API_BASE_URL` avec l'URL de production du backend.

## 🔧 Personnalisation

### Couleurs
Les couleurs sont définies dans la configuration Tailwind dans chaque fichier HTML :
```javascript
colors: {
  primary: { /* couleurs primaires */ },
  secondary: { /* couleurs secondaires */ }
}
```

### Animations
Les animations CSS sont définies dans les balises `<style>` de chaque page.

## 📞 Support

Pour les problèmes liés au frontend :
1. Vérifiez que le backend est en cours d'exécution
2. Consultez la console du navigateur pour les erreurs
3. Testez avec la page `/test.html`

## 🤝 Contribution

Pour contribuer au frontend :
1. Respectez la structure existante
2. Utilisez Tailwind CSS pour le styling
3. Testez sur différents navigateurs
4. Documentez les nouvelles fonctionnalités
