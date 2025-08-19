const express = require('express');
const router = express.Router();

// Configuration pour l'API Together AI
const TOGETHER_API_KEY = process.env.TOGETHER_API_KEY;
const TOGETHER_API_URL = 'https://api.together.xyz/v1/chat/completions';

// Vérification de la santé de l'API
router.get('/health', (req, res) => {
    res.json({
        status: 'OK',
        message: 'API Lexifever fonctionnelle',
        timestamp: new Date().toISOString(),
        apiKeyStatus: TOGETHER_API_KEY ? 'Définie' : 'Non définie'
    });
});

// Route pour générer un texte personnalisé
router.post('/generate-text', async (req, res) => {
    try {
        const { domain, topic, level, tone, length, includeExamples, includeQuestions } = req.body;

        // Validation des paramètres
        if (!domain || !topic || !level) {
            return res.status(400).json({
                error: 'Paramètres manquants',
                required: ['domain', 'topic', 'level']
            });
        }

        // Construction du prompt personnalisé
        const prompt = buildPrompt({
            domain,
            topic, 
            level,
            tone: tone || 'informative',
            length: length || 'medium',
            includeExamples: includeExamples || false,
            includeQuestions: includeQuestions || false
        });

        console.log('🤖 Génération de texte pour:', { domain, topic, level, tone });

        // Appel à l'API Together AI via fetch
        const response = await fetch(TOGETHER_API_URL, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${TOGETHER_API_KEY}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                messages: [{
                    role: "user",
                    content: prompt
                }],
                model: "meta-llama/Llama-3.2-11B-Vision-Instruct-Turbo",
                max_tokens: getMaxTokens(length),
                temperature: getToneTemperature(tone)
            })
        });

        if (!response.ok) {
            throw new Error(`API Together AI error: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        const generatedText = data.choices[0].message.content;

        // Retourner le texte généré
        res.json({
            success: true,
            data: {
                englishText: generatedText,
                parameters: {
                    domain,
                    topic,
                    level,
                    tone,
                    length
                },
                timestamp: new Date().toISOString()
            }
        });

    } catch (error) {
        console.error('❌ Erreur génération de texte:', error);
        res.status(500).json({
            error: 'Erreur lors de la génération du texte',
            message: process.env.NODE_ENV === 'development' ? error.message : 'Erreur interne'
        });
    }
});

// Route pour traduire un texte
router.post('/translate', async (req, res) => {
    try {
        const { text, targetLanguage = 'french' } = req.body;

        if (!text) {
            return res.status(400).json({
                error: 'Texte à traduire manquant'
            });
        }

        const translatePrompt = `Translate the following English text to French. Provide only the translation, no explanations:

${text}`;

        const response = await fetch(TOGETHER_API_URL, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${TOGETHER_API_KEY}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                messages: [{
                    role: "user",
                    content: translatePrompt
                }],
                model: "meta-llama/Llama-3.2-11B-Vision-Instruct-Turbo",
                max_tokens: 2000,
                temperature: 0.3
            })
        });

        if (!response.ok) {
            throw new Error(`API Together AI error: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        const translation = data.choices[0].message.content;

        res.json({
            success: true,
            data: {
                originalText: text,
                translatedText: translation,
                targetLanguage,
                timestamp: new Date().toISOString()
            }
        });

    } catch (error) {
        console.error('❌ Erreur traduction:', error);
        res.status(500).json({
            error: 'Erreur lors de la traduction',
            message: process.env.NODE_ENV === 'development' ? error.message : 'Erreur interne'
        });
    }
});

// Fonction pour construire le prompt personnalisé
function buildPrompt({ domain, topic, level, tone, length, includeExamples, includeQuestions }) {
    const levelDescriptions = {
        beginner: 'beginner level (A1-A2), using simple vocabulary and short sentences',
        intermediate: 'intermediate level (B1-B2), using moderate vocabulary and varied sentence structures', 
        advanced: 'advanced level (C1-C2), using sophisticated vocabulary and complex sentence structures'
    };

    const lengthWords = {
        short: '150-200 words',
        medium: '300-400 words',
        long: '500-600 words'
    };

    let prompt = `Write an educational text in English about "${topic}" in the "${domain}" domain. 

Requirements:
- Target audience: ${levelDescriptions[level]}
- Tone: ${tone}
- Length: approximately ${lengthWords[length]}
- Make it engaging and informative`;

    if (includeExamples) {
        prompt += '\n- Include practical examples to illustrate concepts';
    }

    if (includeQuestions) {
        prompt += '\n- End with 2-3 thought-provoking questions for reflection';
    }

    prompt += '\n\nProvide only the text content, no titles or formatting.';

    return prompt;
}

// Fonction pour déterminer le nombre max de tokens selon la longueur
function getMaxTokens(length) {
    const tokenLimits = {
        short: 300,
        medium: 600,
        long: 900
    };
    return tokenLimits[length] || 600;
}

// Fonction pour ajuster la température selon le tone
function getToneTemperature(tone) {
    const temperatures = {
        informative: 0.3,
        conversational: 0.7,
        formal: 0.2,
        creative: 0.8,
        technical: 0.1
    };
    return temperatures[tone] || 0.5;
}

module.exports = router;
