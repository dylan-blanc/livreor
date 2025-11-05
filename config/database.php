<?php
/**
 * Configuration de la base de données et des constantes de l'application
 */

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'livreor');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuration de l'application
define('BASE_URL', 'http://localhost/livreor/public/');
define('APP_NAME', 'Livreor');
define('APP_VERSION', '1.0.0');

// Configuration des chemins (seulement si ROOT_PATH n'est pas déjà défini)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

define('CONFIG_PATH', ROOT_PATH . '/config');
define('CONTROLLER_PATH', ROOT_PATH . '/controllers');
define('MODEL_PATH', ROOT_PATH . '/models');
define('VIEW_PATH', ROOT_PATH . '/views');
define('INCLUDE_PATH', ROOT_PATH . '/includes');
define('CORE_PATH', ROOT_PATH . '/core');
define('PUBLIC_PATH', ROOT_PATH . '/public');
