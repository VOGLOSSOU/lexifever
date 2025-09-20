#!/bin/bash

# Script de démarrage pour le Frontend Lexifever
# Auteur: VOGLOSSOU

echo "🎨 Démarrage du Frontend Lexifever..."
echo "====================================="

# Vérifier si Node.js est installé
if ! command -v node &> /dev/null; then
    echo "❌ Node.js n'est pas installé. Veuillez l'installer d'abord."
    exit 1
fi

# Installer les dépendances si nécessaire
if [ ! -d "node_modules" ]; then
    echo "📦 Installation des dépendances frontend..."
    npm install
fi

echo "✅ Configuration vérifiée."
echo "🌟 Démarrage du serveur frontend..."
echo "   URL: http://localhost:8080"
echo "   Mode: Développement avec rechargement automatique"
echo ""
echo "⚠️  Assurez-vous que le backend est démarré sur http://localhost:3000"
echo ""

# Ouvrir le navigateur automatiquement (optionnel)
if command -v xdg-open &> /dev/null; then
    echo "🌐 Ouverture du navigateur..."
    sleep 2 && xdg-open http://localhost:8080 &
elif command -v open &> /dev/null; then
    echo "🌐 Ouverture du navigateur..."
    sleep 2 && open http://localhost:8080 &
fi

# Démarrer le serveur de développement
npm run dev
