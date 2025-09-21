# 🔊 Guide de Dépannage Audio - Lexifever

## 🎯 Problème : "Erreur lors de la lecture: synthesis-failed"

### ❌ Symptômes
- Le bouton "Écouter" ne fonctionne pas
- Erreur "synthesis-failed" dans la console
- Aucune voix disponible (0 voix détectées)

### 🔍 Causes Possibles

#### 1. **Voix Non Chargées (Cause la Plus Courante)**
Dans certains navigateurs (Chrome, Edge), les voix sont chargées **de manière asynchrone**.

**Solution :**
```javascript
// Actualiser la page et attendre quelques secondes
// Les voix se chargent généralement après 1-2 secondes
```

#### 2. **Navigateur Non Compatible**
Certains navigateurs anciens ou en mode privé ne supportent pas la synthèse vocale.

**Navigateurs Compatibles :**
- ✅ Chrome/Chromium (recommandé)
- ✅ Firefox
- ✅ Safari
- ✅ Edge
- ❌ Internet Explorer

#### 3. **Permissions Insuffisantes**
Le navigateur bloque l'accès à la synthèse vocale.

**Solution :**
1. Vérifier les paramètres de confidentialité
2. Autoriser l'accès aux fonctionnalités média
3. Désactiver les bloqueurs de publicités si nécessaire

#### 4. **Configuration Système**
Problème avec les paramètres audio du système d'exploitation.

## 🛠️ Solutions Pas à Pas

### **Solution 1 : Actualisation et Attente**
```bash
1. Actualiser la page (F5)
2. Attendre 2-3 secondes
3. Ouvrir la console développeur (F12)
4. Vérifier que des voix sont détectées
5. Réessayer le bouton "Écouter"
```

### **Solution 2 : Test Diagnostic**
```bash
# Accéder à la page de test
http://votredomaine.com/test-audio.html

# Vérifier :
- ✅ API supportée
- 📊 Nombre de voix > 0
- 🇺🇸 Voix anglaises disponibles
```

### **Solution 3 : Configuration Navigateur**

#### **Chrome/Chromium :**
1. Aller dans `chrome://settings/content`
2. Section "Son et média"
3. S'assurer que "Sites peuvent lire des sons" est activé

#### **Firefox :**
1. Taper `about:config` dans la barre d'adresse
2. Rechercher `media.webspeech.synth.enabled`
3. S'assurer que c'est `true`

### **Solution 4 : Mode Incognito**
```bash
# Tester en mode navigation privée
# Cela peut résoudre les problèmes de cache/extensions
```

## 📊 Diagnostic Avancé

### **Console Logs Attendus :**
```
🔊 Initialisation de la synthèse vocale...
✅ 25 voix chargées: Google US English (en-US), etc.
🇺🇸 Utilisation de la voix anglaise: Google US English
▶️ Lecture audio démarrée
⏹️ Lecture audio terminée
```

### **Console Logs Problématiques :**
```
🔊 Initialisation de la synthèse vocale...
❌ 0 voix chargées
⚠️ Aucune voix disponible
```

## 🎵 Test de Fonctionnement

### **Test Simple :**
1. Ouvrir `test-audio.html`
2. Cliquer sur "Test Direct"
3. Vérifier que le son fonctionne

### **Test Avancé :**
1. Générer un texte dans l'application
2. Attendre que le texte soit affiché
3. Cliquer sur "Écouter"
4. Vérifier la lecture

## 🔧 Code Technique

### **Vérification des Voix :**
```javascript
// Méthode correcte pour vérifier les voix
const voices = speechSynthesis.getVoices();
console.log(`${voices.length} voix disponibles`);

// Attendre le chargement asynchrone
speechSynthesis.onvoiceschanged = () => {
    const voices = speechSynthesis.getVoices();
    console.log(`${voices.length} voix chargées`);
};
```

### **Utilisation des Voix :**
```javascript
// Trouver une voix anglaise
const englishVoice = voices.find(voice => voice.lang.startsWith('en'));
if (englishVoice) {
    utterance.voice = englishVoice;
}

// Paramètres optimaux
utterance.rate = 0.9;  // Vitesse légèrement réduite
utterance.pitch = 1.0;  // Hauteur normale
utterance.volume = 1.0; // Volume maximum
```

## 🚨 Problèmes Connus

### **Chrome sur Linux :**
- Les voix peuvent ne pas se charger correctement
- **Solution :** Utiliser Firefox ou installer des voix système

### **Safari sur iOS :**
- Limite de durée pour la synthèse vocale
- **Solution :** Textes courts ou découpage en morceaux

### **Firefox sur Android :**
- Support limité de la synthèse vocale
- **Solution :** Utiliser Chrome Mobile

## 📞 Support Supplémentaire

Si le problème persiste :

1. **Vérifier la Console :** Ouvrir F12 et regarder les erreurs
2. **Tester Différents Navigateurs :** Chrome → Firefox → Safari
3. **Mettre à Jour :** S'assurer que le navigateur est à jour
4. **Redémarrer :** Redémarrer le navigateur/commande

### **Contact Support :**
- Ouvrir un ticket avec les logs de console
- Préciser le navigateur et système d'exploitation
- Inclure les résultats du test diagnostic

---

**Note :** La synthèse vocale dépend des capacités du navigateur et du système d'exploitation. Dans la plupart des cas, actualiser la page résout le problème en permettant aux voix de se charger correctement.