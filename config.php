<?php
/**
 * Configuration de l'application RWHOIS Dashboard
 */

// Configuration de base
define('APP_NAME', 'RWHOIS Dashboard');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, production

// Configuration de la base de données (pour une future implémentation)
define('DB_HOST', 'localhost');
define('DB_NAME', 'rwhois');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');

// Configuration des chemins
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('VIEWS_PATH', BASE_PATH . '/views');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('LOGS_PATH', BASE_PATH . '/logs');

// Configuration de sécurité
define('SECRET_KEY', 'your-secret-key-here-change-in-production');
define('SESSION_LIFETIME', 3600); // 1 heure
define('PASSWORD_MIN_LENGTH', 8);

// Configuration des serveurs RWHOIS
define('RWHOIS_DEFAULT_PORT', 4321);
define('RWHOIS_TIMEOUT', 30); // secondes
define('SSH_TIMEOUT', 60); // secondes

// Configuration des scripts d'installation
define('INSTALL_SCRIPTS_PATH', BASE_PATH . '/scripts');
define('RWHOIS_INSTALL_SCRIPT', INSTALL_SCRIPTS_PATH . '/install-rwhois.sh');
define('RWHOIS_UNINSTALL_SCRIPT', INSTALL_SCRIPTS_PATH . '/uninstall-rwhois.sh');

// Configuration des logs
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR
define('LOG_FILE', LOGS_PATH . '/app.log');
define('ERROR_LOG_FILE', LOGS_PATH . '/error.log');

// Configuration de l'API
define('API_RATE_LIMIT', 100); // requêtes par minute
define('API_TOKEN_EXPIRY', 86400); // 24 heures

// Configuration des emails (pour notifications)
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_FROM', 'noreply@rwhois.local');
define('SMTP_FROM_NAME', 'RWHOIS Dashboard');

// Configuration des notifications
define('NOTIFY_ON_SERVER_INSTALL', true);
define('NOTIFY_ON_SERVER_ERROR', true);
define('NOTIFY_ON_RESOURCE_CHANGE', true);

// Configuration des permissions par défaut
$DEFAULT_PERMISSIONS = [
    'admin' => [
        'servers' => ['view' => true, 'edit' => true, 'delete' => true, 'install' => true],
        'organizations' => ['view' => true, 'edit' => true, 'delete' => true],
        'resources' => ['view' => true, 'edit' => true, 'delete' => true],
        'users' => ['view' => true, 'edit' => true, 'delete' => true]
    ],
    'user' => [
        'servers' => ['view' => true, 'edit' => true, 'delete' => false, 'install' => false],
        'organizations' => ['view' => true, 'edit' => false, 'delete' => false],
        'resources' => ['view' => true, 'edit' => true, 'delete' => false],
        'users' => ['view' => false, 'edit' => false, 'delete' => false]
    ],
    'viewer' => [
        'servers' => ['view' => true, 'edit' => false, 'delete' => false, 'install' => false],
        'organizations' => ['view' => true, 'edit' => false, 'delete' => false],
        'resources' => ['view' => true, 'edit' => false, 'delete' => false],
        'users' => ['view' => false, 'edit' => false, 'delete' => false]
    ]
];

// Configuration des types d'organisations
$ORGANIZATION_TYPES = [
    'ISP' => 'Fournisseur d\'accès internet',
    'Hosting Provider' => 'Hébergeur',
    'Cloud Provider' => 'Fournisseur cloud',
    'Enterprise' => 'Entreprise',
    'Government' => 'Gouvernement',
    'Education' => 'Éducation',
    'Non-Profit' => 'Organisation à but non lucratif'
];

// Configuration des types de ressources
$RESOURCE_TYPES = [
    'ASN' => 'Autonomous System Number',
    'IPv4' => 'Adresse IPv4',
    'IPv6' => 'Adresse IPv6',
    'Domain' => 'Nom de domaine',
    'Email' => 'Adresse email'
];

// Configuration des environnements de serveurs
$SERVER_ENVIRONMENTS = [
    'production' => 'Production',
    'staging' => 'Staging',
    'development' => 'Développement',
    'testing' => 'Test',
    'backup' => 'Sauvegarde'
];

// Configuration des statuts de serveurs
$SERVER_STATUSES = [
    'active' => 'Actif',
    'inactive' => 'Inactif',
    'maintenance' => 'En maintenance',
    'error' => 'Erreur',
    'installing' => 'Installation en cours',
    'uninstalling' => 'Désinstallation en cours'
];

// Fonctions utilitaires de configuration
function getConfig($key, $default = null) {
    return defined($key) ? constant($key) : $default;
}

function isProduction() {
    return APP_ENV === 'production';
}

function isDevelopment() {
    return APP_ENV === 'development';
}

function getLogLevel() {
    return getConfig('LOG_LEVEL', 'INFO');
}

function getDefaultPermissions($role) {
    global $DEFAULT_PERMISSIONS;
    return isset($DEFAULT_PERMISSIONS[$role]) ? $DEFAULT_PERMISSIONS[$role] : [];
}

function getOrganizationTypes() {
    global $ORGANIZATION_TYPES;
    return $ORGANIZATION_TYPES;
}

function getResourceTypes() {
    global $RESOURCE_TYPES;
    return $RESOURCE_TYPES;
}

function getServerEnvironments() {
    global $SERVER_ENVIRONMENTS;
    return $SERVER_ENVIRONMENTS;
}

function getServerStatuses() {
    global $SERVER_STATUSES;
    return $SERVER_STATUSES;
}

function getDbConnection() {
    $host = 'localhost';
    $dbname = 'rwhois';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur connexion DB: " . $e->getMessage());
    }
}

// Configuration des timezones
date_default_timezone_set('Europe/Paris');

// Configuration des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration des sessions
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    if (isProduction()) {
        ini_set('session.cookie_secure', 1);
    }
}

// Création des répertoires nécessaires
$directories = [LOGS_PATH, INSTALL_SCRIPTS_PATH];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}
?> 