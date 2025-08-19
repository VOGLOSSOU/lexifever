#!/bin/bash

# Script de démarrage pour le Backend Lexifever
# Auteur: VOGLOSSOU

echo "🚀 Démarrage du Backend Lexifever..."
echo "===================================="

# Vérifier si Node.js est installé
if ! command -v node &> /dev/null; then
    echo "❌ Node.js n'est pas installé. Veuillez l'installer d'abord."
    exit 1
fi

# Vérifier si le fichier .env existe
if [ ! -f ".env" ]; then
    echo "⚠️  Fichier .env manquant. Création à partir de .env.example..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "✅ Fichier .env créé. Veuillez configurer votre clé API Together AI."
        read -p "Appuyez sur Entrée une fois configuré..."
    else
        echo "❌ Fichier .env.example manquant."
        exit 1
    fi
fi

# Installer les dépendances si nécessaire
if [ ! -d "node_modules" ]; then
    echo "📦 Installation des dépendances..."
    npm install
fi

# Vérifier la configuration
if grep -q "your_together_ai_api_key_here" .env; then
    echo "⚠️  Clé API non configurée dans .env"
    exit 1
fi

echo "✅ Configuration vérifiée."
echo "🌟 Démarrage du serveur backend..."
echo "   API: http://localhost:3000/api/"
echo "   Health: http://localhost:3000/api/health"
echo ""

# Démarrer le serveur
npm start
