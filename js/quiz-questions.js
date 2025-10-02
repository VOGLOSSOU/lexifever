// Configuration des questions de test par domaine
// Chaque domaine a 10 questions spécifiques à son vocabulaire

const QUIZ_QUESTIONS = {
    'Technologie': [
        {
            question: "Quelle est la traduction française de 'machine learning' ?",
            options: ["Apprentissage mécanique", "Apprentissage automatique", "Machine apprenante", "Intelligence mécanique"],
            correctAnswer: 1,
            hint: "C'est un terme technique qui désigne l'apprentissage des machines.",
            explanation: "'Machine learning' se traduit par 'apprentissage automatique' en français."
        },
        {
            question: "Que signifie 'neural network' en français ?",
            options: ["Réseau nerveux", "Réseau neuronal", "Réseau de neurones", "Système nerveux"],
            correctAnswer: 1,
            hint: "C'est inspiré du système nerveux humain.",
            explanation: "Un 'neural network' est un 'réseau neuronal', inspiré du cerveau humain."
        },
        {
            question: "Quelle est la traduction de 'deep learning' ?",
            options: ["Apprentissage profond", "Apprentissage intense", "Apprentissage détaillé", "Apprentissage avancé"],
            correctAnswer: 0,
            hint: "Le mot 'deep' fait référence à la profondeur.",
            explanation: "'Deep learning' signifie 'apprentissage profond' en français."
        },
        {
            question: "Que veut dire 'artificial intelligence' en français ?",
            options: ["Intelligence artificielle", "Intelligence créée", "Intelligence simulée", "Intelligence mécanique"],
            correctAnswer: 0,
            hint: "C'est l'intelligence créée par l'homme.",
            explanation: "'Artificial intelligence' se traduit par 'intelligence artificielle'."
        },
        {
            question: "Quelle est la traduction de 'natural language processing' ?",
            options: ["Traitement naturel du langage", "Traitement du langage naturel", "Langage naturel traité", "Processus de langage naturel"],
            correctAnswer: 1,
            hint: "NLP traite le langage humain naturel.",
            explanation: "'Natural language processing' signifie 'traitement du langage naturel'."
        },
        {
            question: "Que signifie 'algorithm' en français ?",
            options: ["Algorithme", "Analyse", "Application", "Architecture"],
            correctAnswer: 0,
            hint: "C'est une suite d'instructions pour résoudre un problème.",
            explanation: "Un 'algorithm' est un 'algorithme' en français."
        },
        {
            question: "Quelle est la traduction de 'data science' ?",
            options: ["Science des données", "Science informatique", "Science analytique", "Science numérique"],
            correctAnswer: 0,
            hint: "C'est l'étude scientifique des données.",
            explanation: "'Data science' signifie 'science des données' en français."
        },
        {
            question: "Que veut dire 'cloud computing' en français ?",
            options: ["Informatique en nuage", "Informatique distante", "Informatique virtuelle", "Informatique connectée"],
            correctAnswer: 0,
            hint: "Les données sont stockées 'dans les nuages'.",
            explanation: "'Cloud computing' se traduit par 'informatique en nuage'."
        },
        {
            question: "Quelle est la traduction de 'blockchain' ?",
            options: ["Bloc-chaine", "Chaîne de blocs", "Bloc de chaîne", "Chaîne bloquée"],
            correctAnswer: 1,
            hint: "C'est une chaîne de blocs de données.",
            explanation: "Une 'blockchain' est une 'chaîne de blocs' en français."
        },
        {
            question: "Que signifie 'cybersecurity' en français ?",
            options: ["Cybersécurité", "Sécurité informatique", "Sécurité numérique", "Protection cyber"],
            correctAnswer: 0,
            hint: "C'est la sécurité dans le monde numérique.",
            explanation: "'Cybersecurity' signifie 'cybersécurité' en français."
        }
    ],

    'Sciences': [
        {
            question: "Que signifie 'hypothesis' en français ?",
            options: ["Hypoténuse", "Hypothèse", "Hypertension", "Hypertexte"],
            correctAnswer: 1,
            hint: "C'est une supposition scientifique à vérifier.",
            explanation: "Une 'hypothesis' est une 'hypothèse' scientifique."
        },
        {
            question: "Quelle est la traduction de 'molecule' ?",
            options: ["Molécule", "Molaire", "Moulage", "Moulin"],
            correctAnswer: 0,
            hint: "C'est la plus petite particule d'une substance.",
            explanation: "Une 'molecule' est une 'molécule' en chimie."
        },
        {
            question: "Que veut dire 'photosynthesis' en français ?",
            options: ["Photosynthèse", "Photographie", "Photon", "Phosphorescence"],
            correctAnswer: 0,
            hint: "Processus par lequel les plantes produisent de l'énergie.",
            explanation: "'Photosynthesis' signifie 'photosynthèse'."
        },
        {
            question: "Quelle est la traduction de 'evolution' en biologie ?",
            options: ["Évolution", "Évaporation", "Évaluation", "Évasion"],
            correctAnswer: 0,
            hint: "Théorie de Darwin sur l'adaptation des espèces.",
            explanation: "'Evolution' signifie 'évolution' en biologie."
        },
        {
            question: "Que signifie 'quantum physics' ?",
            options: ["Physique quantique", "Physique quantitative", "Physique qualitative", "Physique questionnable"],
            correctAnswer: 0,
            hint: "Étude des particules subatomiques.",
            explanation: "'Quantum physics' signifie 'physique quantique'."
        },
        {
            question: "Quelle est la traduction de 'ecosystem' ?",
            options: ["Écosystème", "Économie", "Écologie", "Écosphère"],
            correctAnswer: 0,
            hint: "Ensemble d'organismes vivant dans un environnement.",
            explanation: "Un 'ecosystem' est un 'écosystème'."
        },
        {
            question: "Que veut dire 'fossil' en français ?",
            options: ["Fossile", "Fossilisé", "Fossé", "Fonction"],
            correctAnswer: 0,
            hint: "Restes d'organismes anciens préservés dans la roche.",
            explanation: "Un 'fossil' est un 'fossile'."
        },
        {
            question: "Quelle est la traduction de 'microorganism' ?",
            options: ["Micro-organisme", "Microscope", "Microcosme", "Micromètre"],
            correctAnswer: 0,
            hint: "Être vivant trop petit pour être vu à l'œil nu.",
            explanation: "Un 'microorganism' est un 'micro-organisme'."
        },
        {
            question: "Que signifie 'astronomy' ?",
            options: ["Astronomie", "Astrologie", "Astroport", "Astronaute"],
            correctAnswer: 0,
            hint: "Science qui étudie les corps célestes.",
            explanation: "'Astronomy' signifie 'astronomie'."
        },
        {
            question: "Quelle est la traduction de 'laboratory' ?",
            options: ["Laboratoire", "Labyrinthe", "Label", "Labour"],
            correctAnswer: 0,
            hint: "Lieu où se font les expériences scientifiques.",
            explanation: "Un 'laboratory' est un 'laboratoire'."
        }
    ],

    'Business': [
        {
            question: "Que signifie 'entrepreneurship' en français ?",
            options: ["Entrepreneuriat", "Entreprise", "Entretien", "Entrée"],
            correctAnswer: 0,
            hint: "Art de créer et gérer une entreprise.",
            explanation: "'Entrepreneurship' signifie 'entrepreneuriat'."
        },
        {
            question: "Quelle est la traduction de 'market share' ?",
            options: ["Part de marché", "Marché partagé", "Marché social", "Marché spécial"],
            correctAnswer: 0,
            hint: "Pourcentage des ventes contrôlé par une entreprise.",
            explanation: "'Market share' signifie 'part de marché'."
        },
        {
            question: "Que veut dire 'investment' ?",
            options: ["Investissement", "Invention", "Investigation", "Invitation"],
            correctAnswer: 0,
            hint: "Placement d'argent pour générer des profits.",
            explanation: "Un 'investment' est un 'investissement'."
        },
        {
            question: "Quelle est la traduction de 'profit margin' ?",
            options: ["Marge bénéficiaire", "Marge marchande", "Marge mobile", "Marge minimale"],
            correctAnswer: 0,
            hint: "Différence entre revenus et coûts.",
            explanation: "'Profit margin' signifie 'marge bénéficiaire'."
        },
        {
            question: "Que signifie 'stakeholder' en français ?",
            options: ["Partie prenante", "Actionnaire", "Partenaire", "Participant"],
            correctAnswer: 0,
            hint: "Personne ou groupe affecté par les décisions d'une entreprise.",
            explanation: "Un 'stakeholder' est une 'partie prenante'."
        },
        {
            question: "Quelle est la traduction de 'supply chain' ?",
            options: ["Chaîne d'approvisionnement", "Chaîne d'offre", "Chaîne de commande", "Chaîne de contrôle"],
            correctAnswer: 0,
            hint: "Processus de production et distribution.",
            explanation: "'Supply chain' signifie 'chaîne d'approvisionnement'."
        },
        {
            question: "Que veut dire 'marketing strategy' ?",
            options: ["Stratégie marketing", "Stratégie marchande", "Stratégie de marché", "Stratégie marchandise"],
            correctAnswer: 0,
            hint: "Plan pour promouvoir un produit ou service.",
            explanation: "'Marketing strategy' signifie 'stratégie marketing'."
        },
        {
            question: "Quelle est la traduction de 'customer satisfaction' ?",
            options: ["Satisfaction client", "Satisfaction consommatrice", "Satisfaction commerciale", "Satisfaction concurrentielle"],
            correctAnswer: 0,
            hint: "Degré de contentement des clients.",
            explanation: "'Customer satisfaction' signifie 'satisfaction client'."
        },
        {
            question: "Que signifie 'brand equity' ?",
            options: ["Capital marque", "Équité de marque", "Équilibre de marque", "Équivalence de marque"],
            correctAnswer: 0,
            hint: "Valeur perçue d'une marque par les consommateurs.",
            explanation: "'Brand equity' signifie 'capital marque'."
        },
        {
            question: "Quelle est la traduction de 'competitive advantage' ?",
            options: ["Avantage concurrentiel", "Avantage compétitif", "Avantage commercial", "Avantage comparatif"],
            correctAnswer: 0,
            hint: "Atout qui distingue une entreprise de ses concurrents.",
            explanation: "'Competitive advantage' signifie 'avantage concurrentiel'."
        }
    ],

    'Santé': [
        {
            question: "Que signifie 'nutrition' en français ?",
            options: ["Nutrition", "Nourriture", "Nourrisson", "Nourricier"],
            correctAnswer: 0,
            hint: "Science qui étudie les aliments et leur impact sur la santé.",
            explanation: "'Nutrition' garde le même sens en français."
        },
        {
            question: "Quelle est la traduction de 'cardiovascular' ?",
            options: ["Cardiovasculaire", "Cardiaque", "Circulatoire", "Cardiologique"],
            correctAnswer: 0,
            hint: "Relatif au cœur et aux vaisseaux sanguins.",
            explanation: "'Cardiovascular' signifie 'cardiovasculaire'."
        },
        {
            question: "Que veut dire 'immunity' en médecine ?",
            options: ["Immunité", "Immigration", "Imminence", "Immobilité"],
            correctAnswer: 0,
            hint: "Capacité du corps à se défendre contre les maladies.",
            explanation: "'Immunity' signifie 'immunité' en médecine."
        },
        {
            question: "Quelle est la traduction de 'diagnosis' ?",
            options: ["Diagnostic", "Diagnostique", "Diagnose", "Dialogue"],
            correctAnswer: 0,
            hint: "Identification d'une maladie par un médecin.",
            explanation: "Un 'diagnosis' est un 'diagnostic'."
        },
        {
            question: "Que signifie 'therapy' ?",
            options: ["Thérapie", "Théorie", "Théâtral", "Théologique"],
            correctAnswer: 0,
            hint: "Traitement médical pour soigner une maladie.",
            explanation: "Une 'therapy' est une 'thérapie'."
        },
        {
            question: "Quelle est la traduction de 'wellness' ?",
            options: ["Bien-être", "Bienveillance", "Bienfaisance", "Bienfait"],
            correctAnswer: 0,
            hint: "État de bonne santé physique et mentale.",
            explanation: "'Wellness' signifie 'bien-être'."
        },
        {
            question: "Que veut dire 'pharmaceutical' ?",
            options: ["Pharmaceutique", "Pharmacologique", "Pharmaceutique", "Pharmacien"],
            correctAnswer: 0,
            hint: "Relatif aux médicaments et à leur production.",
            explanation: "'Pharmaceutical' signifie 'pharmaceutique'."
        },
        {
            question: "Quelle est la traduction de 'rehabilitation' ?",
            options: ["Réadaptation", "Réhabilitation", "Réhabiliter", "Réhabilité"],
            correctAnswer: 0,
            hint: "Processus de récupération après une maladie ou accident.",
            explanation: "'Rehabilitation' signifie 'réadaptation'."
        },
        {
            question: "Que signifie 'epidemic' ?",
            options: ["Épidémie", "Épiderme", "Épisode", "Épître"],
            correctAnswer: 0,
            hint: "Propagation rapide d'une maladie dans une population.",
            explanation: "Une 'epidemic' est une 'épidémie'."
        },
        {
            question: "Quelle est la traduction de 'mental health' ?",
            options: ["Santé mentale", "Santé mentale", "Santé spirituelle", "Santé morale"],
            correctAnswer: 0,
            hint: "État de bien-être psychologique.",
            explanation: "'Mental health' signifie 'santé mentale'."
        }
    ],

    'Arts': [
        {
            question: "Que signifie 'creativity' en français ?",
            options: ["Créativité", "Création", "Créature", "Créateur"],
            correctAnswer: 0,
            hint: "Capacité à produire des idées originales.",
            explanation: "'Creativity' signifie 'créativité'."
        },
        {
            question: "Quelle est la traduction de 'painting' en art ?",
            options: ["Peinture", "Paysage", "Palette", "Pinceau"],
            correctAnswer: 0,
            hint: "Technique artistique utilisant des couleurs sur une toile.",
            explanation: "'Painting' signifie 'peinture' en art."
        },
        {
            question: "Que veut dire 'sculpture' ?",
            options: ["Sculpture", "Sculpter", "Sculture", "Scénario"],
            correctAnswer: 0,
            hint: "Art de créer des formes en 3D.",
            explanation: "'Sculpture' garde le même sens en français."
        },
        {
            question: "Quelle est la traduction de 'composition' en musique ?",
            options: ["Composition", "Composition", "Composite", "Composant"],
            correctAnswer: 0,
            hint: "Création d'une œuvre musicale.",
            explanation: "'Composition' signifie 'composition' en musique."
        },
        {
            question: "Que signifie 'performance' en arts du spectacle ?",
            options: ["Performance", "Perfection", "Préformation", "Préférence"],
            correctAnswer: 0,
            hint: "Représentation artistique devant un public.",
            explanation: "Une 'performance' est une 'représentation' artistique."
        },
        {
            question: "Quelle est la traduction de 'exhibition' ?",
            options: ["Exposition", "Exhibition", "Exécution", "Exercice"],
            correctAnswer: 0,
            hint: "Présentation publique d'œuvres d'art.",
            explanation: "Une 'exhibition' est une 'exposition' d'art."
        },
        {
            question: "Que veut dire 'inspiration' en art ?",
            options: ["Inspiration", "Inspiration", "Inspirateur", "Inspiré"],
            correctAnswer: 0,
            hint: "Source de motivation créative.",
            explanation: "'Inspiration' garde le même sens en art."
        },
        {
            question: "Quelle est la traduction de 'masterpiece' ?",
            options: ["Chef-d'œuvre", "Maître-œuvre", "Maître pièce", "Maître œuvre"],
            correctAnswer: 0,
            hint: "Œuvre d'art exceptionnelle.",
            explanation: "Un 'masterpiece' est un 'chef-d'œuvre'."
        },
        {
            question: "Que signifie 'abstract art' ?",
            options: ["Art abstrait", "Art absent", "Art absolu", "Art abouti"],
            correctAnswer: 0,
            hint: "Art qui ne représente pas la réalité de manière figurative.",
            explanation: "'Abstract art' signifie 'art abstrait'."
        },
        {
            question: "Quelle est la traduction de 'curator' en art ?",
            options: ["Conservateur", "Créateur", "Critique", "Collectionneur"],
            correctAnswer: 0,
            hint: "Personne qui organise les expositions dans un musée.",
            explanation: "Un 'curator' est un 'conservateur' de musée."
        }
    ],

    'Voyage': [
        {
            question: "Que signifie 'destination' en français ?",
            options: ["Destination", "Destinée", "Déstination", "Destin"],
            correctAnswer: 0,
            hint: "Lieu où l'on se rend en voyage.",
            explanation: "'Destination' garde le même sens en français."
        },
        {
            question: "Quelle est la traduction de 'itinerary' ?",
            options: ["Itinéraire", "Itinérant", "Italien", "Itératif"],
            correctAnswer: 0,
            hint: "Plan détaillé d'un voyage.",
            explanation: "Un 'itinerary' est un 'itinéraire'."
        },
        {
            question: "Que veut dire 'accommodation' ?",
            options: ["Hébergement", "Accommodement", "Accompagnement", "Accommodant"],
            correctAnswer: 0,
            hint: "Lieu où séjourner pendant un voyage.",
            explanation: "'Accommodation' signifie 'hébergement'."
        },
        {
            question: "Quelle est la traduction de 'backpacking' ?",
            options: ["Randonnée sac à dos", "Voyage en bus", "Voyage organisé", "Voyage d'affaires"],
            correctAnswer: 0,
            hint: "Voyage à pied avec un sac à dos.",
            explanation: "'Backpacking' signifie 'randonnée sac à dos'."
        },
        {
            question: "Que signifie 'cultural exchange' ?",
            options: ["Échange culturel", "Échange commercial", "Échange monétaire", "Échange scolaire"],
            correctAnswer: 0,
            hint: "Partage d'expériences culturelles entre personnes.",
            explanation: "'Cultural exchange' signifie 'échange culturel'."
        },
        {
            question: "Quelle est la traduction de 'adventure tourism' ?",
            options: ["Tourisme d'aventure", "Tourisme aventureux", "Tourisme d'aventures", "Tourisme d'avant-garde"],
            correctAnswer: 0,
            hint: "Voyages impliquant des activités sportives et risquées.",
            explanation: "'Adventure tourism' signifie 'tourisme d'aventure'."
        },
        {
            question: "Que veut dire 'sustainable tourism' ?",
            options: ["Tourisme durable", "Tourisme soutenable", "Tourisme supportable", "Tourisme soutenu"],
            correctAnswer: 0,
            hint: "Tourisme respectueux de l'environnement.",
            explanation: "'Sustainable tourism' signifie 'tourisme durable'."
        },
        {
            question: "Quelle est la traduction de 'visa' ?",
            options: ["Visa", "Visage", "Vision", "Visite"],
            correctAnswer: 0,
            hint: "Document officiel pour entrer dans un pays.",
            explanation: "'Visa' garde le même sens en français."
        },
        {
            question: "Que signifie 'jet lag' ?",
            options: ["Décalage horaire", "Jet de lag", "Jet lagué", "Jet de luxe"],
            correctAnswer: 0,
            hint: "Fatigue due au changement de fuseau horaire.",
            explanation: "'Jet lag' signifie 'décalage horaire'."
        },
        {
            question: "Quelle est la traduction de 'hiking trail' ?",
            options: ["Sentier de randonnée", "Sentier de marche", "Sentier de balade", "Sentier de promenade"],
            correctAnswer: 0,
            hint: "Chemin aménagé pour la randonnée pédestre.",
            explanation: "'Hiking trail' signifie 'sentier de randonnée'."
        }
    ],

    'Sport': [
        {
            question: "Que signifie 'championship' en français ?",
            options: ["Championnat", "Champion", "Championne", "Champignon"],
            correctAnswer: 0,
            hint: "Compétition pour déterminer le meilleur.",
            explanation: "Un 'championship' est un 'championnat'."
        },
        {
            question: "Quelle est la traduction de 'athlete' ?",
            options: ["Athlète", "Atelier", "Attitude", "Attaque"],
            correctAnswer: 0,
            hint: "Personne qui pratique un sport à haut niveau.",
            explanation: "Un 'athlete' est un 'athlète'."
        },
        {
            question: "Que veut dire 'tournament' ?",
            options: ["Tournoi", "Tourisme", "Tourment", "Tournage"],
            correctAnswer: 0,
            hint: "Compétition sportive avec plusieurs participants.",
            explanation: "Un 'tournament' est un 'tournoi'."
        },
        {
            question: "Quelle est la traduction de 'training' en sport ?",
            options: ["Entraînement", "Entrainement", "Entraîneur", "Entraîné"],
            correctAnswer: 0,
            hint: "Préparation physique pour une compétition.",
            explanation: "'Training' signifie 'entraînement' en sport."
        },
        {
            question: "Que signifie 'olympic games' ?",
            options: ["Jeux olympiques", "Jeux olympiens", "Jeux olympiades", "Jeux olympiques"],
            correctAnswer: 0,
            hint: "Compétition sportive internationale tous les 4 ans.",
            explanation: "'Olympic games' signifie 'jeux olympiques'."
        },
        {
            question: "Quelle est la traduction de 'fitness' ?",
            options: ["Fitness", "Forme physique", "Santé", "Condition"],
            correctAnswer: 1,
            hint: "État de bonne santé physique.",
            explanation: "'Fitness' signifie 'forme physique'."
        },
        {
            question: "Que veut dire 'coach' en sport ?",
            options: ["Entraîneur", "Coach", "Coaching", "Cours"],
            correctAnswer: 0,
            hint: "Personne qui entraîne une équipe sportive.",
            explanation: "Un 'coach' est un 'entraîneur' en sport."
        },
        {
            question: "Quelle est la traduction de 'stadium' ?",
            options: ["Stade", "Stade", "Statue", "Station"],
            correctAnswer: 0,
            hint: "Lieu où se déroulent les compétitions sportives.",
            explanation: "Un 'stadium' est un 'stade'."
        },
        {
            question: "Que signifie 'medal' ?",
            options: ["Médaille", "Médecin", "Médical", "Médian"],
            correctAnswer: 0,
            hint: "Récompense décernée aux vainqueurs.",
            explanation: "Une 'medal' est une 'médaille'."
        },
        {
            question: "Quelle est la traduction de 'score' en sport ?",
            options: ["Score", "Marquer", "Marquage", "Marque"],
            correctAnswer: 0,
            hint: "Nombre de points marqués dans un match.",
            explanation: "Un 'score' est le 'score' ou 'marque' d'un match."
        }
    ],

    'Histoire': [
        {
            question: "Que signifie 'civilization' en français ?",
            options: ["Civilisation", "Civilité", "Civil", "Civique"],
            correctAnswer: 0,
            hint: "Société organisée avec des institutions complexes.",
            explanation: "Une 'civilization' est une 'civilisation'."
        },
        {
            question: "Quelle est la traduction de 'empire' ?",
            options: ["Empire", "Empereur", "Empirique", "Emploi"],
            correctAnswer: 0,
            hint: "Grand État dirigé par un empereur.",
            explanation: "Un 'empire' est un 'empire'."
        },
        {
            question: "Que veut dire 'revolution' en histoire ?",
            options: ["Révolution", "Révélation", "Révolutionnaire", "Révolu"],
            correctAnswer: 0,
            hint: "Changement radical et rapide dans la société.",
            explanation: "Une 'revolution' est une 'révolution' historique."
        },
        {
            question: "Quelle est la traduction de 'archaeology' ?",
            options: ["Archéologie", "Archéologue", "Archétype", "Archéologie"],
            correctAnswer: 0,
            hint: "Science qui étudie les civilisations anciennes.",
            explanation: "'Archaeology' signifie 'archéologie'."
        },
        {
            question: "Que signifie 'heritage' ?",
            options: ["Héritage", "Héritier", "Hérédité", "Hérésie"],
            correctAnswer: 0,
            hint: "Patrimoine culturel transmis aux générations futures.",
            explanation: "Le 'heritage' est l' 'héritage' culturel."
        },
        {
            question: "Quelle est la traduction de 'dynasty' ?",
            options: ["Dynastie", "Dynastique", "Dynamique", "Dynastie"],
            correctAnswer: 0,
            hint: "Famille royale qui règne sur un pays pendant plusieurs générations.",
            explanation: "Une 'dynasty' est une 'dynastie'."
        },
        {
            question: "Que veut dire 'colonialism' ?",
            options: ["Colonialisme", "Colonie", "Colonisateur", "Colonial"],
            correctAnswer: 0,
            hint: "Politique d'établissement de colonies.",
            explanation: "'Colonialism' signifie 'colonialisme'."
        },
        {
            question: "Quelle est la traduction de 'renaissance' ?",
            options: ["Renaissance", "Renaissant", "Renaissance", "Renaître"],
            correctAnswer: 0,
            hint: "Période de renouvellement culturel en Europe.",
            explanation: "La 'renaissance' est la 'renaissance'."
        },
        {
            question: "Que signifie 'artifact' en archéologie ?",
            options: ["Artéfact", "Artefact", "Artificiel", "Artiste"],
            correctAnswer: 0,
            hint: "Objet créé par l'homme dans le passé.",
            explanation: "Un 'artifact' est un 'artéfact' archéologique."
        },
        {
            question: "Quelle est la traduction de 'chronicle' ?",
            options: ["Chronique", "Chronologie", "Chronomètre", "Chronologique"],
            correctAnswer: 0,
            hint: "Récit historique des événements dans l'ordre chronologique.",
            explanation: "Une 'chronicle' est une 'chronique' historique."
        }
    ],

    'Cuisine': [
        {
            question: "Que signifie 'gastronomy' en français ?",
            options: ["Gastronomie", "Gastro-entérite", "Gastronome", "Gastro"],
            correctAnswer: 0,
            hint: "Art et science de la bonne cuisine.",
            explanation: "'Gastronomy' signifie 'gastronomie'."
        },
        {
            question: "Quelle est la traduction de 'recipe' en cuisine ?",
            options: ["Recette", "Réception", "Récipient", "Récit"],
            correctAnswer: 0,
            hint: "Instructions pour préparer un plat.",
            explanation: "Une 'recipe' est une 'recette' de cuisine."
        },
        {
            question: "Que veut dire 'ingredient' ?",
            options: ["Ingrédient", "Ingrédients", "Ingredient", "Ingrédient"],
            correctAnswer: 0,
            hint: "Élément qui compose un plat.",
            explanation: "Un 'ingredient' est un 'ingrédient'."
        },
        {
            question: "Quelle est la traduction de 'cuisine' en français ?",
            options: ["Cuisine", "Cuisinier", "Cuisinière", "Cuisiner"],
            correctAnswer: 0,
            hint: "Art de préparer les aliments.",
            explanation: "'Cuisine' garde le même sens en français."
        },
        {
            question: "Que signifie 'appetizer' ?",
            options: ["Apéritif", "Entrée", "Hors-d'œuvre", "Amuse-bouche"],
            correctAnswer: 2,
            hint: "Petit plat servi avant le repas principal.",
            explanation: "Un 'appetizer' est un 'hors-d'œuvre'."
        },
        {
            question: "Quelle est la traduction de 'baking' ?",
            options: ["Cuisson au four", "Cuisson à la vapeur", "Cuisson à l'eau", "Cuisson au grill"],
            correctAnswer: 0,
            hint: "Méthode de cuisson utilisant un four.",
            explanation: "'Baking' signifie 'cuisson au four'."
        },
        {
            question: "Que veut dire 'spices' en cuisine ?",
            options: ["Épices", "Épice", "Épicé", "Épicerie"],
            correctAnswer: 0,
            hint: "Substances aromatiques utilisées en cuisine.",
            explanation: "'Spices' signifie 'épices'."
        },
        {
            question: "Quelle est la traduction de 'culinary' ?",
            options: ["Culinaire", "Culturel", "Culiné", "Culinaire"],
            correctAnswer: 0,
            hint: "Relatif à la cuisine et à l'art culinaire.",
            explanation: "'Culinary' signifie 'culinaire'."
        },
        {
            question: "Que signifie 'gourmet' ?",
            options: ["Gourmet", "Gourmand", "Goûteur", "Goût"],
            correctAnswer: 0,
            hint: "Personne qui apprécie la bonne cuisine.",
            explanation: "Un 'gourmet' est un amateur de bonne cuisine."
        },
        {
            question: "Quelle est la traduction de 'pastry' ?",
            options: ["Pâtisserie", "Pâté", "Pâte", "Pâtissier"],
            correctAnswer: 0,
            hint: "Art de faire des gâteaux et desserts.",
            explanation: "'Pastry' signifie 'pâtisserie'."
        }
    ]
};

// Fonction pour obtenir les questions d'un domaine spécifique
function getQuestionsForDomain(domain) {
    return QUIZ_QUESTIONS[domain] || QUIZ_QUESTIONS['Technologie']; // Fallback vers Technologie
}

// Fonction pour vérifier si un domaine a des questions
function hasQuestionsForDomain(domain) {
    return QUIZ_QUESTIONS.hasOwnProperty(domain);
}

// Liste des domaines disponibles pour le quiz
const AVAILABLE_QUIZ_DOMAINS = Object.keys(QUIZ_QUESTIONS);