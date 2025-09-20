<?php
/**
 * Classe Domain - Modèle pour les domaines
 * Gère les domaines et leurs sujets associés
 */

class Domain {
    private $db;
    private $config;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->config = require __DIR__ . '/../Config/config.php';
    }

    /**
     * Récupérer tous les domaines actifs
     */
    public function getAll() {
        try {
            $sql = "SELECT id, name, description, image_url, created_at
                    FROM domains
                    WHERE is_active = 1
                    ORDER BY name ASC";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des domaines: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer un domaine par son ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT id, name, description, image_url, is_active, created_at, updated_at
                    FROM domains
                    WHERE id = ? AND is_active = 1";
            return $this->db->fetchOne($sql, [$id]);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération du domaine {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer un domaine par son nom
     */
    public function getByName($name) {
        try {
            $sql = "SELECT id, name, description, image_url, is_active, created_at, updated_at
                    FROM domains
                    WHERE name = ? AND is_active = 1";
            return $this->db->fetchOne($sql, [$name]);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération du domaine '{$name}': " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer les sujets d'un domaine
     */
    public function getTopics($domainId) {
        try {
            $sql = "SELECT id, name, description, image_url, created_at
                    FROM topics
                    WHERE domain_id = ? AND is_active = 1
                    ORDER BY name ASC";
            return $this->db->fetchAll($sql, [$domainId]);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des sujets du domaine {$domainId}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Créer un nouveau domaine
     */
    public function create($data) {
        try {
            // Validation des données
            if (empty($data['name'])) {
                throw new Exception("Le nom du domaine est obligatoire");
            }

            $insertData = [
                'name' => trim($data['name']),
                'description' => $data['description'] ?? '',
                'image_url' => $data['image_url'] ?? '',
                'is_active' => $data['is_active'] ?? true
            ];

            $id = $this->db->insert('domains', $insertData);

            // Créer les sujets par défaut si configurés
            if (isset($this->config['domains'][$insertData['name']])) {
                $this->createDefaultTopics($id, $insertData['name']);
            }

            return $id;

        } catch (Exception $e) {
            error_log("Erreur lors de la création du domaine: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Créer les sujets par défaut pour un domaine
     */
    private function createDefaultTopics($domainId, $domainName) {
        if (!isset($this->config['domains'][$domainName])) {
            return;
        }

        $topics = $this->config['domains'][$domainName];

        foreach ($topics as $topicName) {
            try {
                $topicData = [
                    'domain_id' => $domainId,
                    'name' => $topicName,
                    'description' => $this->generateTopicDescription($topicName),
                    'image_url' => '',
                    'is_active' => true
                ];

                $this->db->insert('topics', $topicData);
            } catch (Exception $e) {
                error_log("Erreur lors de la création du sujet '{$topicName}': " . $e->getMessage());
            }
        }
    }

    /**
     * Générer une description automatique pour un sujet
     */
    private function generateTopicDescription($topicName) {
        $descriptions = [
            'Intelligence Artificielle' => 'Apprentissage automatique, réseaux de neurones, traitement du langage naturel et applications IA.',
            'Développement Web' => 'HTML, CSS, JavaScript, frameworks front-end, back-end, responsive design et accessibilité.',
            'Cybersécurité' => 'Protection des données, cryptographie, hacking éthique, sécurité des réseaux et menaces informatiques.',
            'Blockchain' => 'Cryptomonnaies, contrats intelligents, NFT, applications décentralisées et technologie blockchain.',
            'Développement Mobile' => 'Applications iOS et Android, React Native, Flutter, développement natif et expérience utilisateur mobile.',
            'Cloud Computing' => 'AWS, Azure, Google Cloud, services cloud, infrastructure as a service et déploiement d\'applications.',
            'Astronomie' => 'Étoiles, planètes, galaxies, trous noirs, exploration spatiale et découvertes cosmiques.',
            'Biologie' => 'Génétique, évolution, écosystèmes, biologie moléculaire et recherche médicale.',
            'Physique' => 'Mécanique quantique, relativité, thermodynamique, électromagnétisme et physique des particules.',
            'Chimie' => 'Réactions chimiques, chimie organique, biochimie, matériaux et applications industrielles.',
            'Marketing Digital' => 'SEO, réseaux sociaux, publicité en ligne, analytics et stratégies de contenu.',
            'Entrepreneuriat' => 'Création d\'entreprise, innovation, financement, business plan et développement commercial.',
            'Finance' => 'Investissement, marchés financiers, analyse financière, cryptomonnaies et gestion de portefeuille.',
            'Management' => 'Leadership, gestion d\'équipe, stratégie d\'entreprise, ressources humaines et organisation.',
            'Nutrition' => 'Alimentation équilibrée, vitamines, régimes alimentaires, métabolisme et santé digestive.',
            'Fitness' => 'Exercice physique, musculation, cardio, récupération et programmes d\'entraînement.',
            'Médecine' => 'Diagnostic médical, traitements, recherche médicale, technologies de santé et prévention.',
            'Bien-être Mental' => 'Gestion du stress, méditation, psychologie positive, thérapies et équilibre vie-travail.',
            'Peinture' => 'Techniques artistiques, histoire de l\'art, mouvements picturaux et créativité visuelle.',
            'Musique' => 'Théorie musicale, instruments, composition, genres musicaux et production audio.',
            'Littérature' => 'Écriture créative, analyse littéraire, genres narratifs et expression écrite.',
            'Cinéma' => 'Réalisation, scénario, montage, histoire du cinéma et techniques audiovisuelles.',
            'Destinations Exotiques' => 'Cultures locales, traditions, gastronomie et expériences authentiques de voyage.',
            'Aventure et Nature' => 'Randonnée, camping, sports extrêmes, écotourisme et exploration naturelle.',
            'Voyage Urbain' => 'Architecture, musées, vie nocturne, transport urbain et découverte des villes.',
            'Conseils de Voyage' => 'Planification, budget, sécurité, logistique et astuces pour voyageurs.',
            'Football' => 'Techniques, tactiques, histoire du football, compétitions et analyse de matchs.',
            'Basketball' => 'Stratégies de jeu, entraînement, NBA, compétitions internationales et techniques.',
            'Tennis' => 'Techniques de frappe, tournois, grands chelems, entraînement et équipement.',
            'Sports Olympiques' => 'Jeux olympiques, athlétisme, natation, gymnastique et compétitions internationales.',
            'Histoire Ancienne' => 'Civilisations antiques, empires, archéologie et patrimoine historique.',
            'Histoire Moderne' => 'Révolutions, guerres mondiales, évolutions sociales et transformations politiques.',
            'Histoire Contemporaine' => 'XXe siècle, événements récents, géopolitique moderne et société actuelle.',
            'Biographies' => 'Personnages historiques, leaders, inventeurs, artistes et figures marquantes.',
            'Cuisine Française' => 'Gastronomie française, techniques culinaires, vins et traditions régionales.',
            'Cuisine Internationale' => 'Spécialités du monde, fusion culinaire, épices et découvertes gustatives.',
            'Pâtisserie' => 'Desserts, techniques de pâtisserie, chocolaterie et créations sucrées.',
            'Cuisine Saine' => 'Alimentation équilibrée, superaliments, recettes nutritives et bien-être.'
        ];

        return $descriptions[$topicName] ?? 'Découvrez ce sujet fascinant et enrichissez vos connaissances.';
    }

    /**
     * Mettre à jour un domaine
     */
    public function update($id, $data) {
        try {
            $updateData = [];

            if (isset($data['name'])) {
                $updateData['name'] = trim($data['name']);
            }

            if (isset($data['description'])) {
                $updateData['description'] = $data['description'];
            }

            if (isset($data['image_url'])) {
                $updateData['image_url'] = $data['image_url'];
            }

            if (isset($data['is_active'])) {
                $updateData['is_active'] = (bool)$data['is_active'];
            }

            if (empty($updateData)) {
                throw new Exception("Aucune donnée à mettre à jour");
            }

            return $this->db->update('domains', $updateData, ['id' => $id]);

        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du domaine {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprimer un domaine (désactiver)
     */
    public function delete($id) {
        try {
            return $this->db->update('domains', ['is_active' => false], ['id' => $id]);
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression du domaine {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprimer définitivement un domaine
     */
    public function hardDelete($id) {
        try {
            return $this->db->delete('domains', ['id' => $id]);
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression définitive du domaine {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Compter le nombre total de domaines
     */
    public function count() {
        try {
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM domains WHERE is_active = 1");
            return (int)$result['count'];
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des domaines: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Vérifier si un domaine existe
     */
    public function exists($id) {
        try {
            $result = $this->db->fetchOne("SELECT 1 FROM domains WHERE id = ? AND is_active = 1", [$id]);
            return $result !== false;
        } catch (Exception $e) {
            error_log("Erreur lors de la vérification du domaine {$id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les statistiques des domaines
     */
    public function getStats() {
        try {
            $stats = [];

            // Nombre total de domaines
            $stats['total_domains'] = $this->count();

            // Nombre total de sujets
            $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM topics WHERE is_active = 1");
            $stats['total_topics'] = (int)$result['count'];

            // Domaines les plus populaires (basé sur les textes générés)
            $sql = "SELECT d.name, COUNT(gt.id) as text_count
                    FROM domains d
                    LEFT JOIN generated_texts gt ON gt.domain = d.name
                    WHERE d.is_active = 1
                    GROUP BY d.id, d.name
                    ORDER BY text_count DESC
                    LIMIT 5";
            $stats['popular_domains'] = $this->db->fetchAll($sql);

            return $stats;

        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des statistiques: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Initialiser les données par défaut
     */
    public function initializeDefaultData() {
        try {
            foreach ($this->config['domains'] as $domainName => $topics) {
                // Vérifier si le domaine existe déjà
                $existing = $this->getByName($domainName);
                if (!$existing) {
                    $domainData = [
                        'name' => $domainName,
                        'description' => $this->generateDomainDescription($domainName),
                        'image_url' => '',
                        'is_active' => true
                    ];

                    $domainId = $this->create($domainData);
                    error_log("Domaine '{$domainName}' créé avec l'ID {$domainId}");
                }
            }

            return true;

        } catch (Exception $e) {
            error_log("Erreur lors de l'initialisation des données: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Générer une description automatique pour un domaine
     */
    private function generateDomainDescription($domainName) {
        $descriptions = [
            'Technologie' => 'Explorez le monde fascinant de la technologie moderne et ses innovations.',
            'Sciences' => 'Découvrez les merveilles de la science et les avancées de la recherche.',
            'Business' => 'Plongez dans l\'univers du commerce, de l\'entrepreneuriat et de la finance.',
            'Santé' => 'Apprenez tout sur la santé, le bien-être et les soins médicaux.',
            'Arts' => 'Explorez le monde créatif des arts, de la musique et de la littérature.',
            'Voyage' => 'Découvrez des destinations extraordinaires et des expériences de voyage uniques.',
            'Sport' => 'Plongez dans l\'univers passionnant du sport et de la compétition.',
            'Histoire' => 'Explorez les événements passés qui ont façonné notre monde.',
            'Cuisine' => 'Découvrez l\'art culinaire et les saveurs du monde entier.'
        ];

        return $descriptions[$domainName] ?? 'Découvrez ce domaine fascinant et enrichissez vos connaissances.';
    }
}