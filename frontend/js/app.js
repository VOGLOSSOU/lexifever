// Configuration de l'API
const API_BASE_URL = window.location.origin + '/api';

console.log('🚀 Lexifever App.js chargé !');
console.log('📍 API Base URL:', API_BASE_URL);
console.log('📄 Page actuelle:', window.location.pathname);

// Configuration des domaines et leurs sujets
const DOMAINS_CONFIG = {
    'Technologie': [
        {
            name: 'Intelligence Artificielle',
            description: 'Apprentissage automatique, réseaux de neurones, traitement du langage naturel et applications IA.',
            image: 'https://images.unsplash.com/photo-1677442135136-760c813a6a13?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1632&q=80'
        },
        {
            name: 'Développement Web',
            description: 'HTML, CSS, JavaScript, frameworks front-end, back-end, responsive design et accessibilité.',
            image: 'https://images.unsplash.com/photo-1547658719-da2b51169166?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1464&q=80'
        },
        {
            name: 'Cybersécurité',
            description: 'Protection des données, cryptographie, hacking éthique, sécurité des réseaux et menaces informatiques.',
            image: 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Blockchain',
            description: 'Cryptomonnaies, contrats intelligents, NFT, applications décentralisées et technologie blockchain.',
            image: 'https://images.unsplash.com/photo-1639762681057-408e52192e55?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1632&q=80'
        },
        {
            name: 'Développement Mobile',
            description: 'Applications iOS et Android, React Native, Flutter, développement natif et expérience utilisateur mobile.',
            image: 'https://images.unsplash.com/photo-1526498460520-4c246339dccb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Cloud Computing',
            description: 'AWS, Azure, Google Cloud, services cloud, infrastructure as a service et déploiement d\'applications.',
            image: 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        }
    ],
    'Sciences': [
        {
            name: 'Astronomie',
            description: 'Étoiles, planètes, galaxies, trous noirs, exploration spatiale et découvertes cosmiques.',
            image: 'https://images.unsplash.com/photo-1446776877081-d282a0f896e2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1472&q=80'
        },
        {
            name: 'Biologie',
            description: 'Génétique, évolution, écosystèmes, biologie moléculaire et recherche médicale.',
            image: 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80'
        },
        {
            name: 'Physique',
            description: 'Mécanique quantique, relativité, thermodynamique, électromagnétisme et physique des particules.',
            image: 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Chimie',
            description: 'Réactions chimiques, chimie organique, biochimie, matériaux et applications industrielles.',
            image: 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        }
    ],
    'Business': [
        {
            name: 'Marketing Digital',
            description: 'SEO, réseaux sociaux, publicité en ligne, analytics et stratégies de contenu.',
            image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1415&q=80'
        },
        {
            name: 'Entrepreneuriat',
            description: 'Création d\'entreprise, innovation, financement, business plan et développement commercial.',
            image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Finance',
            description: 'Investissement, marchés financiers, analyse financière, cryptomonnaies et gestion de portefeuille.',
            image: 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Management',
            description: 'Leadership, gestion d\'équipe, stratégie d\'entreprise, ressources humaines et organisation.',
            image: 'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        }
    ],
    'Santé': [
        {
            name: 'Nutrition',
            description: 'Alimentation équilibrée, vitamines, régimes alimentaires, métabolisme et santé digestive.',
            image: 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1453&q=80'
        },
        {
            name: 'Fitness',
            description: 'Exercice physique, musculation, cardio, récupération et programmes d\'entraînement.',
            image: 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Médecine',
            description: 'Diagnostic médical, traitements, recherche médicale, technologies de santé et prévention.',
            image: 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80'
        },
        {
            name: 'Bien-être Mental',
            description: 'Gestion du stress, méditation, psychologie positive, thérapies et équilibre vie-travail.',
            image: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        }
    ],
    'Arts': [
        {
            name: 'Peinture',
            description: 'Techniques artistiques, histoire de l\'art, mouvements picturaux et créativité visuelle.',
            image: 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80'
        },
        {
            name: 'Musique',
            description: 'Théorie musicale, instruments, composition, genres musicaux et production audio.',
            image: 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Littérature',
            description: 'Écriture créative, analyse littéraire, genres narratifs et expression écrite.',
            image: 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Cinéma',
            description: 'Réalisation, scénario, montage, histoire du cinéma et techniques audiovisuelles.',
            image: 'https://images.unsplash.com/photo-1489599735734-79b4af4e84c2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        }
    ],
    'Voyage': [
        {
            name: 'Destinations Exotiques',
            description: 'Cultures locales, traditions, gastronomie et expériences authentiques de voyage.',
            image: 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1435&q=80'
        },
        {
            name: 'Aventure et Nature',
            description: 'Randonnée, camping, sports extrêmes, écotourisme et exploration naturelle.',
            image: 'https://images.unsplash.com/photo-1551632811-561732d1e306?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Voyage Urbain',
            description: 'Architecture, musées, vie nocturne, transport urbain et découverte des villes.',
            image: 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Conseils de Voyage',
            description: 'Planification, budget, sécurité, logistique et astuces pour voyageurs.',
            image: 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1435&q=80'
        }
    ],
    'Sport': [
        {
            name: 'Football',
            description: 'Techniques, tactiques, histoire du football, compétitions et analyse de matchs.',
            image: 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1393&q=80'
        },
        {
            name: 'Basketball',
            description: 'Stratégies de jeu, entraînement, NBA, compétitions internationales et techniques.',
            image: 'https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1459&q=80'
        },
        {
            name: 'Tennis',
            description: 'Techniques de frappe, tournois, grands chelems, entraînement et équipement.',
            image: 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Sports Olympiques',
            description: 'Jeux olympiques, athlétisme, natation, gymnastique et compétitions internationales.',
            image: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        }
    ],
    'Histoire': [
        {
            name: 'Histoire Ancienne',
            description: 'Civilisations antiques, empires, archéologie et patrimoine historique.',
            image: 'https://images.unsplash.com/photo-1539650116574-75c0c6d73f6e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Histoire Moderne',
            description: 'Révolutions, guerres mondiales, évolutions sociales et transformations politiques.',
            image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Histoire Contemporaine',
            description: 'XXe siècle, événements récents, géopolitique moderne et société actuelle.',
            image: 'https://images.unsplash.com/photo-1553729459-efe14ef6055d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Biographies',
            description: 'Personnages historiques, leaders, inventeurs, artistes et figures marquantes.',
            image: 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        }
    ],
    'Cuisine': [
        {
            name: 'Cuisine Française',
            description: 'Gastronomie française, techniques culinaires, vins et traditions régionales.',
            image: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
        },
        {
            name: 'Cuisine Internationale',
            description: 'Spécialités du monde, fusion culinaire, épices et découvertes gustatives.',
            image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1481&q=80'
        },
        {
            name: 'Pâtisserie',
            description: 'Desserts, techniques de pâtisserie, chocolaterie et créations sucrées.',
            image: 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1489&q=80'
        },
        {
            name: 'Cuisine Saine',
            description: 'Alimentation équilibrée, superaliments, recettes nutritives et bien-être.',
            image: 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1453&q=80'
        }
    ]
};

// Utilitaires pour le localStorage
const Storage = {
    set: (key, value) => localStorage.setItem(key, JSON.stringify(value)),
    get: (key) => {
        try {
            return JSON.parse(localStorage.getItem(key));
        } catch {
            return null;
        }
    },
    remove: (key) => localStorage.removeItem(key)
};

// Gestion des paramètres de session
const SessionManager = {
    // Sauvegarder les paramètres de génération
    saveParams: (params) => {
        Storage.set('lexifever_params', params);
    },
    
    // Récupérer les paramètres
    getParams: () => {
        return Storage.get('lexifever_params') || {};
    },
    
    // Nettoyer la session
    clear: () => {
        Storage.remove('lexifever_params');
    }
};

// Utilitaires pour les requêtes API
const ApiClient = {
    async request(endpoint, options = {}) {
        const url = `${API_BASE_URL}${endpoint}`;
        const config = {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        };

        console.log('🌐 Requête API:', url, config);

        try {
            const response = await fetch(url, config);
            console.log('📡 Réponse API:', response.status, response.statusText);

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || `Erreur HTTP: ${response.status}`);
            }

            console.log('✅ Données reçues:', data);
            return data;
        } catch (error) {
            console.error('❌ Erreur API:', error);
            throw error;
        }
    },

    // Générer un texte
    async generateText(params) {
        return this.request('/generate-text', {
            method: 'POST',
            body: JSON.stringify(params)
        });
    },

    // Traduire un texte
    async translateText(text) {
        return this.request('/translate', {
            method: 'POST',
            body: JSON.stringify({ text })
        });
    },

    // Vérifier la santé de l'API
    async healthCheck() {
        return this.request('/health');
    }
};

// Utilitaires UI
const UI = {
    // Afficher un indicateur de chargement
    showLoading(element, message = 'Chargement...') {
        element.innerHTML = `
            <div class="flex items-center justify-center p-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 mr-3"></div>
                <span class="text-gray-600">${message}</span>
            </div>
        `;
    },

    // Afficher une erreur
    showError(element, message) {
        element.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Erreur</h3>
                        <div class="mt-2 text-sm text-red-700">${message}</div>
                    </div>
                </div>
            </div>
        `;
    },

    // Afficher un message de succès
    showSuccess(element, message) {
        element.innerHTML = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Succès</h3>
                        <div class="mt-2 text-sm text-green-700">${message}</div>
                    </div>
                </div>
            </div>
        `;
    }
};

// Gestionnaire pour la page de sélection de sujet
const TopicPageHandler = {
    init() {
        console.log('🎯 TopicPageHandler.init() appelé');

        // Récupérer le domaine depuis l'URL ou le localStorage
        const urlParams = new URLSearchParams(window.location.search);
        const domain = urlParams.get('domain') || SessionManager.getParams().domain;

        console.log('📂 Domaine détecté:', domain);

        if (domain) {
            SessionManager.saveParams({ domain });
            this.loadTopicsForDomain(domain);
            this.updatePageTitle(domain);
        } else {
            console.log('❌ Aucun domaine trouvé, redirection vers select-domain.html');
            // Rediriger vers la sélection de domaine si aucun domaine n'est spécifié
            window.location.href = 'select-domain.html';
        }
    },

    updatePageTitle(domain) {
        // Mettre à jour le titre de la page
        const titleElement = document.querySelector('h1 span');
        if (titleElement) {
            titleElement.textContent = `Sujets - ${domain}`;
        }

        // Mettre à jour le breadcrumb
        const breadcrumbElement = document.querySelector('.breadcrumb-domain');
        if (breadcrumbElement) {
            breadcrumbElement.textContent = domain;
        }
    },

    loadTopicsForDomain(domain) {
        console.log('📚 Chargement des sujets pour le domaine:', domain);

        const topics = DOMAINS_CONFIG[domain];
        if (!topics) {
            console.error(`❌ Domaine non trouvé: ${domain}`);
            console.log('📋 Domaines disponibles:', Object.keys(DOMAINS_CONFIG));
            return;
        }

        console.log(`✅ ${topics.length} sujets trouvés pour ${domain}`);

        const container = document.getElementById('topics-container');
        if (!container) {
            console.error('❌ Conteneur des sujets non trouvé (topics-container)');
            return;
        }

        console.log('📦 Conteneur trouvé, génération du HTML...');

        // Générer le HTML pour les sujets
        container.innerHTML = topics.map(topic => this.createTopicHTML(topic)).join('');

        console.log('✅ HTML généré et injecté');

        // Ajouter les gestionnaires d'événements
        this.setupTopicEventListeners();
    },

    createTopicHTML(topic) {
        return `
            <div class="group topic-item cursor-pointer" onclick="TopicPageHandler.selectTopic('${topic.name}')">
                <div class="relative overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 group-hover:shadow-2xl group-hover:-translate-y-2">
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="${topic.image}" alt="${topic.name}" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-xl font-bold mb-2">${topic.name}</h3>
                        <p class="text-sm text-gray-200 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            ${topic.description}
                        </p>
                    </div>
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
                            <i class="fas fa-arrow-right text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    setupTopicEventListeners() {
        // Les événements sont gérés par onclick dans le HTML généré
        console.log('Gestionnaires d\'événements configurés pour les sujets');
    },

    selectTopic(topic) {
        const params = SessionManager.getParams();
        params.topic = topic;
        SessionManager.saveParams(params);

        console.log('Sujet sélectionné:', topic, 'pour le domaine:', params.domain);

        // Rediriger vers la page de personnalisation
        window.location.href = 'customize-text.html';
    }
};

// Gestionnaire pour la page de personnalisation
const CustomizePageHandler = {
    init() {
        // Récupérer les paramètres de session
        const params = SessionManager.getParams();

        if (!params.domain || !params.topic) {
            // Rediriger vers la sélection de domaine si les paramètres sont manquants
            window.location.href = 'select-domain.html';
            return;
        }

        // Mettre à jour l'interface avec les paramètres
        this.updateBreadcrumb(params);
        this.updatePageContent(params);

        const form = document.getElementById('customize-form');
        if (form) {
            form.addEventListener('submit', this.handleSubmit.bind(this));
        }

        // Pré-remplir avec les paramètres existants
        this.loadSavedParams();
    },

    updateBreadcrumb(params) {
        // Mettre à jour le breadcrumb avec le domaine et le sujet sélectionnés
        const domainElement = document.querySelector('.breadcrumb-domain');
        const topicElement = document.querySelector('.breadcrumb-topic');

        if (domainElement) {
            domainElement.textContent = params.domain;
        }

        if (topicElement) {
            topicElement.textContent = params.topic;
        }
    },

    updatePageContent(params) {
        // Mettre à jour le titre de la page
        const titleElement = document.querySelector('h1 span');
        if (titleElement) {
            titleElement.textContent = `Personnalisez votre texte - ${params.topic}`;
        }

        // Mettre à jour la description
        const descriptionElement = document.querySelector('.topic-description');
        if (descriptionElement) {
            descriptionElement.textContent = `Configurez les paramètres pour générer un texte personnalisé sur ${params.topic} dans le domaine ${params.domain}.`;
        }
    },

    loadSavedParams() {
        const params = SessionManager.getParams();
        
        // Pré-sélectionner les valeurs sauvegardées
        Object.keys(params).forEach(key => {
            const element = document.querySelector(`[name="${key}"]`);
            if (element) {
                if (element.type === 'radio') {
                    const radio = document.querySelector(`[name="${key}"][value="${params[key]}"]`);
                    if (radio) radio.checked = true;
                } else if (element.type === 'checkbox') {
                    element.checked = params[key];
                } else {
                    element.value = params[key];
                }
            }
        });
    },

    async handleSubmit(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const params = SessionManager.getParams();
        
        // Récupérer tous les paramètres du formulaire
        params.level = formData.get('level');
        params.tone = formData.get('tone');
        params.length = formData.get('length');
        params.includeExamples = formData.has('include-examples');
        params.includeQuestions = formData.has('include-questions');
        
        // Sauvegarder les paramètres
        SessionManager.saveParams(params);
        
        // Rediriger vers la page de résultat
        window.location.href = 'result.html';
    }
};

// Gestionnaire de l'historique des textes
const HistoryManager = {
    // Sauvegarder un texte généré
    saveText(textData) {
        const history = this.getHistory();
        const textEntry = {
            id: Date.now().toString(),
            timestamp: new Date().toISOString(),
            ...textData
        };

        history.unshift(textEntry); // Ajouter au début

        // Limiter à 50 textes maximum
        if (history.length > 50) {
            history.splice(50);
        }

        Storage.set('lexifever_history', history);
        return textEntry.id;
    },

    // Récupérer l'historique
    getHistory() {
        return Storage.get('lexifever_history') || [];
    },

    // Supprimer un texte de l'historique
    deleteText(id) {
        const history = this.getHistory();
        const filteredHistory = history.filter(item => item.id !== id);
        Storage.set('lexifever_history', filteredHistory);
    },

    // Nettoyer tout l'historique
    clearHistory() {
        Storage.remove('lexifever_history');
    }
};

// Gestionnaire pour la page de résultat
const ResultPageHandler = {
    async init() {
        const params = SessionManager.getParams();

        // Vérifier que nous avons tous les paramètres nécessaires
        if (!params.domain || !params.topic || !params.level) {
            this.showError('Paramètres manquants. Veuillez recommencer le processus.');
            return;
        }

        // Afficher les paramètres dans l'interface
        this.displayParameters(params);

        // Générer le texte
        await this.generateText(params);

        // Ajouter les gestionnaires d'événements pour les boutons
        this.setupEventListeners();
    },

    displayParameters(params) {
        // Afficher les paramètres sélectionnés dans l'interface
        const paramElements = {
            domain: document.getElementById('selected-domain'),
            topic: document.getElementById('selected-topic'),
            level: document.getElementById('selected-level'),
            tone: document.getElementById('selected-tone'),
            length: document.getElementById('selected-length')
        };
        
        Object.keys(paramElements).forEach(key => {
            const element = paramElements[key];
            if (element && params[key]) {
                element.textContent = params[key];
            }
        });
    },

    async generateText(params) {
        const englishContainer = document.getElementById('english-text');
        const frenchContainer = document.getElementById('french-text');
        
        if (!englishContainer || !frenchContainer) {
            console.error('Conteneurs de texte non trouvés');
            return;
        }
        
        try {
            // Afficher le chargement
            UI.showLoading(englishContainer, 'Génération du texte en cours...');
            UI.showLoading(frenchContainer, 'En attente...');
            
            // Générer le texte anglais
            const textResponse = await ApiClient.generateText(params);
            const englishText = textResponse.data.englishText;
            
            // Afficher le texte anglais
            englishContainer.innerHTML = `<p class="text-gray-800 leading-relaxed">${englishText}</p>`;
            
            // Traduire en français
            UI.showLoading(frenchContainer, 'Traduction en cours...');
            const translationResponse = await ApiClient.translateText(englishText);
            const frenchText = translationResponse.data.translatedText;
            
            // Afficher la traduction française
            frenchContainer.innerHTML = `<p class="text-gray-800 leading-relaxed">${frenchText}</p>`;

            // Sauvegarder le texte dans l'historique
            const textId = HistoryManager.saveText({
                parameters: params,
                englishText: englishText,
                frenchText: frenchText
            });

            // Stocker l'ID du texte actuel pour les actions (sauvegarde, partage)
            this.currentTextId = textId;

        } catch (error) {
            console.error('Erreur lors de la génération:', error);
            this.showError(`Erreur lors de la génération du texte: ${error.message}`);
            UI.showError(englishContainer, `Erreur lors de la génération du texte: ${error.message}`);
            UI.showError(frenchContainer, 'Impossible de générer la traduction');
        }
    },

    showError(message) {
        // Afficher une notification d'erreur
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        document.body.appendChild(errorDiv);

        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            if (errorDiv.parentElement) {
                errorDiv.remove();
            }
        }, 5000);
    },

    setupEventListeners() {
        // Bouton de sauvegarde
        const saveButton = document.getElementById('save-button');
        if (saveButton) {
            saveButton.addEventListener('click', () => {
                if (this.currentTextId) {
                    this.showSaveConfirmation();
                }
            });
        }

        // Bouton de partage
        const shareButton = document.getElementById('share-button');
        if (shareButton) {
            shareButton.addEventListener('click', () => {
                this.shareText();
            });
        }

        // Bouton pour générer un nouveau texte
        const newTextButton = document.getElementById('new-text-button');
        if (newTextButton) {
            newTextButton.addEventListener('click', () => {
                window.location.href = 'select-domain.html';
            });
        }
    },

    showSaveConfirmation() {
        // Afficher une confirmation que le texte a été sauvegardé
        const saveButton = document.getElementById('save-button');
        if (saveButton) {
            const originalContent = saveButton.innerHTML;
            saveButton.innerHTML = '<i class="fas fa-check mr-2"></i> Sauvegardé !';
            saveButton.classList.add('bg-green-500', 'hover:bg-green-600');
            saveButton.classList.remove('bg-white', 'hover:bg-primary-50', 'text-primary-600');
            saveButton.classList.add('text-white');

            setTimeout(() => {
                saveButton.innerHTML = originalContent;
                saveButton.classList.remove('bg-green-500', 'hover:bg-green-600', 'text-white');
                saveButton.classList.add('bg-white', 'hover:bg-primary-50', 'text-primary-600');
            }, 2000);
        }
    },

    async shareText() {
        const params = SessionManager.getParams();
        const shareData = {
            title: `Lexifever - ${params.topic}`,
            text: `J'ai généré un texte sur "${params.topic}" avec Lexifever !`,
            url: window.location.href
        };

        try {
            if (navigator.share) {
                await navigator.share(shareData);
            } else {
                // Fallback: copier l'URL dans le presse-papiers
                await navigator.clipboard.writeText(window.location.href);

                const shareButton = document.getElementById('share-button');
                if (shareButton) {
                    const originalContent = shareButton.innerHTML;
                    shareButton.innerHTML = '<i class="fas fa-check mr-2"></i> Lien copié !';

                    setTimeout(() => {
                        shareButton.innerHTML = originalContent;
                    }, 2000);
                }
            }
        } catch (error) {
            console.error('Erreur lors du partage:', error);
        }
    },

    showError(message) {
        const container = document.getElementById('main-content') || document.body;
        UI.showError(container, message);
    }
};

// Initialisation automatique selon la page
document.addEventListener('DOMContentLoaded', () => {
    const path = window.location.pathname;
    console.log('🔄 Initialisation automatique pour:', path);

    if (path.includes('select-topic.html')) {
        console.log('📋 Initialisation TopicPageHandler');
        TopicPageHandler.init();
    } else if (path.includes('customize-text.html')) {
        console.log('⚙️ Initialisation CustomizePageHandler');
        CustomizePageHandler.init();
    } else if (path.includes('result.html')) {
        console.log('📊 Initialisation ResultPageHandler');
        ResultPageHandler.init();
    } else {
        console.log('❓ Aucun gestionnaire pour cette page');
    }
});
