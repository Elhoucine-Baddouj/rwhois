<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';
session_start();


// Charger la configuration
require_once __DIR__ . '/config.php';

// Redirection obligatoire vers /login si non connecté
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
if (!isset($_SESSION['user_id']) && $path !== 'login') {
    header('Location: /login');
    exit;
}
// Route de déconnexion
if ($path === 'logout') {
    session_destroy();
    header('Location: /login');
    exit;
}

// Gestion de la route /login
if ($path === 'login') {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $pdo = getDbConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: /dashboard');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    }
    include __DIR__ . '/views/auth/login.php';
    exit;
}

// Autoloader simple
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Router simple
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = trim($path, '/');

// Routes
$routes = [
    '' => 'DashboardController@index',
    'dashboard' => 'DashboardController@index',
    'servers' => 'ServerController@index',
    'servers/status' => 'ServerController@status',
    'servers/refreshStatus' => 'ServerController@refreshStatus',
    'servers/control' => 'ServerController@control',
    'servers/logs' => 'ServerController@logs',
    'servers/info' => 'ServerController@info',
    'servers/edit' => 'ServerController@edit',
    'servers/delete' => 'ServerController@delete',
    'organizations' => 'OrganizationController@index',
    'organizations/add' => 'OrganizationController@add',
    'organizations/edit' => 'OrganizationController@edit',
    'organizations/delete' => 'OrganizationController@delete',
    'organizations/view' => 'OrganizationController@view',
    'users' => 'UserController@index',
    'users/add' => 'UserController@add',
    'users/edit' => 'UserController@edit',
    'users/delete' => 'UserController@delete',
    'users/view' => 'UserController@view',
    'api/servers' => 'ApiController@servers',
    'api/organizations' => 'ApiController@organizations',
    'api/users' => 'ApiController@users',
    'network_resources' => 'NetworkResourceController@index',
    'network_resources/add' => 'NetworkResourceController@add',
    'network_resources/edit' => 'NetworkResourceController@edit',
    'network_resources/delete' => 'NetworkResourceController@delete',
];

// Router
if (isset($routes[$path])) {
    $route = $routes[$path];
    list($controller, $method) = explode('@', $route);
    $controllerClass = "Controllers\\$controller";
    
    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass();
        $controllerInstance->$method();
    } else {
        http_response_code(404);
        echo "Controller not found: $controllerClass";
    }
} else {
    http_response_code(404);
    echo "Page not found: $path";
}
?> 