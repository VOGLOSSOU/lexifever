# 🔧 Guide de Gestion des Quotas API Gemini

## 📋 Problème Identifié

Vous rencontrez une erreur **500 Internal Server Error** lors de la génération de texte. Cette erreur est causée par un **dépassement du quota de l'API Google Gemini**.

### Message d'erreur typique :
```
Quota API dépassé. Veuillez réessayer dans quelques minutes. Détails: RESOURCE_EXHAUSTED
```

## 🔍 Comprendre les Quotas

### Limites de l'API Gemini (version gratuite) :
- **15 requêtes par minute** maximum
- **Quota quotidien** limité
- **Temps d'attente** entre les requêtes

### Causes courantes :
1. **Trop de requêtes rapprochées** (moins d'1 minute d'intervalle)
2. **Paramètres trop complexes** (textes longs, niveau avancé)
3. **Utilisation intensive** pendant les tests

## ✅ Solutions Immédiates

### 1. **Attendre et Réessayer**
```javascript
// L'API indique généralement le temps d'attente nécessaire
// Dans l'erreur : "retryDelay": "51s" = attendre 51 secondes
```

### 2. **Réduire la Fréquence des Requêtes**
- Évitez de cliquer plusieurs fois sur "Générer"
- Attendez au moins **1 minute** entre chaque génération
- Utilisez le bouton "Régénérer" avec modération

### 3. **Simplifier les Paramètres**
- **Niveau** : Préférez "Intermédiaire" plutôt que "Avancé"
- **Longueur** : Choisissez "Court" ou "Moyen" plutôt que "Long"
- **Tonalité** : Évitez "Créatif" ou "Technique" si possible

### 4. **Utiliser le Cache**
- L'application met automatiquement en cache les résultats
- Les mêmes paramètres donneront le même résultat sans appel API

## 🚀 Solutions Long Terme

### 1. **Obtenir une Clé API Payante**
```bash
# Aller sur Google AI Studio
# Créer un nouveau projet
# Activer l'API Gemini Pro (payant)
# Quotas beaucoup plus élevés
```

### 2. **Implémenter un Système de File d'Attente**
- Stocker les requêtes en attente
- Les traiter progressivement
- Éviter la surcharge de l'API

### 3. **Optimiser les Prompts**
- Réduire la longueur des prompts
- Utiliser des formulations plus simples
- Combiner plusieurs petites requêtes

## 📊 Monitoring des Quotas

### Vérifier l'État de l'API :
```javascript
// Endpoint de santé
GET /api/health
```

### Statistiques d'Utilisation :
```javascript
// Via le dashboard admin
GET /api/stats
```

## 🛠️ Configuration Technique

### Paramètres dans `config.php` :
```php
'gemini' => [
    'api_key' => 'votre_clé_api',
    'timeout' => 30,
    'max_retries' => 3,
    'cache_duration' => 3600,
]
```

### Gestion d'Erreurs Améliorée :
- ✅ Détection automatique des erreurs de quota
- ✅ Messages d'erreur explicites
- ✅ Suggestions de résolution
- ✅ Retry automatique (à implémenter)

## 🎯 Recommandations

### Pour les Tests :
1. Utilisez des paramètres simples
2. Attendez entre chaque test
3. Testez un domaine/sujet à la fois

### Pour la Production :
1. Surveillez les quotas régulièrement
2. Implémentez des limites côté utilisateur
3. Préparez un plan B (API alternative)

### Alternatives à Gemini :
- **OpenAI GPT** (plus cher mais plus fiable)
- **Anthropic Claude** (bon compromis)
- **Together AI** (modèles open source)

## 📞 Support

Si le problème persiste :
1. Vérifiez les logs PHP : `/opt/lampp/logs/php_error_log`
2. Consultez les logs d'API : `logs/api_requests.log`
3. Testez avec des paramètres minimaux
4. Contactez le support Google AI Studio

---

**Note** : Cette erreur est normale avec l'API gratuite de Google Gemini. Elle indique que votre application fonctionne correctement, mais que vous avez atteint les limites d'utilisation gratuite.