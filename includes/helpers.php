<?php
/**
 * ===============================================
 * FONCTIONS UTILITAIRES ESSENTIELLES - MVC STARTER
 * ===============================================
 * 
 * Ce fichier contient toutes les fonctions utilitaires nécessaires
 * pour développer une application MVC sécurisée et maintenable.
 * 
 */

// ===============================================
// SÉCURITÉ - PROTECTION XSS
// ===============================================

/**
 * Sécurise l'affichage d'une chaîne de caractères (protection XSS)
 * 
 * Cette fonction est ESSENTIELLE pour éviter les attaques XSS.
 * Elle convertit les caractères spéciaux HTML en entités HTML.
 * 
 * @param string $string La chaîne à sécuriser
 * @return string La chaîne sécurisée
 * 
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// ===============================================
// NAVIGATION - URLS ET REDIRECTIONS
// ===============================================

/**
 * Génère une URL absolue basée sur la configuration BASE_URL
 * 
 * Cette fonction garantit des URLs cohérentes dans toute l'application
 * et facilite le déploiement sur différents environnements.
 * 
 * @param string $path Le chemin relatif (optionnel)
 * @return string L'URL absolue complète
 * 
 */
function url($path = '')
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * Effectue une redirection HTTP vers une URL
 * 
 * Utilisée pour rediriger l'utilisateur après une action
 * (connexion, création, modification, etc.)
 * 
 * @param string $path Le chemin de destination (optionnel, par défaut : accueil)
 * 
 */
function redirect($path = '') {
    $url = url($path);
    header("Location: $url");
    exit;
}

// ===============================================
// MESSAGES FLASH - FEEDBACK UTILISATEUR
// ===============================================

/**
 * Définit un message flash pour l'affichage à la prochaine page
 * 
 * Les messages flash permettent d'afficher des notifications
 * après une redirection (succès, erreur, information).
 * 
 * @param string $type Le type de message ('success', 'error', 'info', 'warning')
 * @param string $message Le contenu du message

 */
function set_flash($type, $message) {
    $_SESSION['flash_messages'][$type][] = $message;
}

/**
 * Récupère et supprime les messages flash (pour affichage unique)
 * 
 * Cette fonction récupère les messages flash et les supprime automatiquement
 * pour éviter qu'ils s'affichent plusieurs fois.
 * 
 * @param string|null $type Le type spécifique à récupérer (optionnel)
 * @return array Les messages flash
 * 
 */
function get_flash_messages($type = null) {
    if (!isset($_SESSION['flash_messages'])) {
        return [];
    }

    if ($type) {
        $messages = $_SESSION['flash_messages'][$type] ?? [];
        unset($_SESSION['flash_messages'][$type]);
        return $messages;
    }

    $messages = $_SESSION['flash_messages'];
    unset($_SESSION['flash_messages']);
    return $messages;
}

// ===============================================
// REQUÊTES HTTP - MÉTHODES ET PARAMÈTRES
// ===============================================

/**
 * Vérifie si la requête HTTP actuelle est en méthode POST
 * 
 * Utilisée pour différencier l'affichage d'un formulaire (GET)
 * du traitement de ce formulaire (POST).
 * 
 * @return bool true si POST, false sinon
 * 
 */
function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Vérifie si la requête HTTP actuelle est en méthode GET
 * 
 * Utilisée pour les affichages, recherches, et navigation.
 * 
 * @return bool true si GET, false sinon
 * 
 */
function is_get() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Récupère la valeur d'un paramètre POST de manière sécurisée
 * 
 * Évite les erreurs "Undefined index" et permet des valeurs par défaut.
 * 
 * @param string $key Le nom du paramètre POST
 * @param mixed $default La valeur par défaut si le paramètre n'existe pas
 * @return mixed La valeur du paramètre ou la valeur par défaut
 * 
 */
function post($key, $default = null) {
    return $_POST[$key] ?? $default;
}

/**
 * Récupère la valeur d'un paramètre GET de manière sécurisée
 * 
 * Utilisée pour les paramètres d'URL, recherches, pagination, filtres.
 * 
 * @param string $key Le nom du paramètre GET
 * @param mixed $default La valeur par défaut si le paramètre n'existe pas
 * @return mixed La valeur du paramètre ou la valeur par défaut
 * 
 */
function get($key, $default = null) {
    return $_GET[$key] ?? $default;
}

// ===============================================
// NAVIGATION - ROUTES ET LIENS ACTIFS
// ===============================================

/**
 * Retourne la route courante basée sur l'URL
 * 
 * Utilisée pour déterminer quelle page est actuellement affichée
 * et appliquer les styles appropriés aux liens de navigation.
 * 
 * @return string La route courante (ex: 'documentation/mvc')
 * 
 */
function current_route() {
    $url = $_GET['url'] ?? '';
    $url = rtrim($url, '/');
    return $url;
}

/**
 * Vérifie si un lien de navigation est actif
 * 
 * Compare la route courante avec le chemin du lien pour déterminer
 * si le lien doit être marqué comme actif dans la navigation.
 * 
 * @param string $path Le chemin du lien à vérifier
 * @return bool true si le lien est actif, false sinon
 * 
 */
function is_active_link($path) {
    $current = current_route();
    $path = ltrim($path, '/');
    
    // Page d'accueil
    if (empty($path) && empty($current)) {
        return true;
    }
    
    // Correspondance exacte
    if ($current === $path) {
        return true;
    }
    
    // Correspondance partielle pour les sections principales uniquement
    // Éviter que "documentation" soit actif sur "documentation/mvc"
    if (!empty($path) && !empty($current)) {
        $currentParts = explode('/', $current);
        $pathParts = explode('/', $path);
        
        // Si le path est une section principale (ex: "documentation")
        // et que la route courante commence par cette section
        // mais a plus de segments, alors ce n'est pas actif
        if (count($pathParts) === 1 && count($currentParts) > 1) {
            return false;
        }
        
        // Correspondance partielle normale
        if (strpos($current, $path) === 0) {
            return true;
        }
    }
    
    return false;
}

// ===============================================
// SÉCURITÉ - PROTECTION CSRF
// ===============================================

/**
 * Génère un token CSRF unique pour sécuriser les formulaires
 * 
 * Cette fonction crée un token aléatoirement sécurisé qui doit être inclus
 * dans tous les formulaires pour prévenir les attaques CSRF.
 * Le token est stocké en session pour vérification ultérieure.
 * 
 * @return string Le token CSRF généré
 * 
 */
function csrf_token() {
    // Démarrer la session si elle n'est pas déjà active
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Générer un nouveau token s'il n'existe pas
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie la validité d'un token CSRF
 * 
 * Cette fonction compare le token fourni avec celui stocké en session
 * pour s'assurer que la requête provient bien du formulaire légitime.
 * 
 * @param string $token Le token CSRF à vérifier
 * @return bool true si le token est valide, false sinon
 * 
 */
function verify_csrf_token($token) {
    // Démarrer la session si elle n'est pas déjà active
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Vérifier que le token existe en session
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    // Comparaison sécurisée des tokens (évite les attaques timing)
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Régénère un nouveau token CSRF
 * 
 * Utilisée après une action sensible pour invalider l'ancien token
 * et forcer la génération d'un nouveau pour les prochains formulaires.
 * 
 * @return string Le nouveau token CSRF généré
 * 
 */
function regenerate_csrf_token() {
    // Démarrer la session si elle n'est pas déjà active
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Supprimer l'ancien token et en générer un nouveau
    unset($_SESSION['csrf_token']);
    return csrf_token();
}

// ==================================================================
// ============================  AJOUTS  ============================
// ==================================================================



function clean_input($data)
{
    $data = (string)$data;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validate_name($name)
{
    // Entre 2 et 50 caractères
    if (strlen($name) < 2 || strlen($name) > 50) return false;

    // Lettres, espaces et tirets uniquement
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-]+$/', $name)) return false;

    return true;
}

function validate_password($password)
{
    // Minimum 5 caractères
    if (strlen($password) < 5) return false;

    // Au moins 1 minuscule
    if (!preg_match('/[a-z]/', $password)) return false;

    // Au moins 1 majuscule  
    if (!preg_match('/[A-Z]/', $password)) return false;

    // Au moins 1 chiffre
    if (!preg_match('/\d/', $password)) return false;

    return true;
}

function format_username($username)
{
    $username = trim($username);
    // Gérer les espaces (pour utilisateurs avec plusieurs Noms)
    $username = ucwords(strtolower($username));
    // Gérer les tirets (pour les utilisateurs avec des prénoms composés)
    $username = preg_replace_callback('/-([a-z])/', function ($matches) {
        return '-' . strtoupper($matches[1]);
    }, $username);
    return $username;
}

function is_logged_in()
{
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    // Mettre à jour la dernière activité
    $_SESSION['last_activity'] = time();

    return true;
}

function sess_destroy()
{
    set_flash('success', 'Déconnexion réussie !');

    if (session_status() === PHP_SESSION_ACTIVE) {
        unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['last_activity']);
        // Régénérer l'ID pour invalider l'ancienne session côté navigateur
        session_regenerate_id(true);
    }
    redirect('');
}
