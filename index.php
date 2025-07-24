<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';
session_start();

// Charger la configuration
require_once __DIR__ . '/config.php';

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