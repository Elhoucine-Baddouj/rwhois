<?php
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
    'servers/add' => 'ServerController@add',
    'servers/edit' => 'ServerController@edit',
    'servers/delete' => 'ServerController@delete',
    'organizations' => 'OrganizationController@index',
    'organizations/add' => 'OrganizationController@add',
    'organizations/edit' => 'OrganizationController@edit',
    'organizations/delete' => 'OrganizationController@delete',
    'resources' => 'ResourceController@index',
    'resources/add' => 'ResourceController@add',
    'resources/edit' => 'ResourceController@edit',
    'resources/delete' => 'ResourceController@delete',
    'users' => 'UserController@index',
    'users/add' => 'UserController@add',
    'users/edit' => 'UserController@edit',
    'users/delete' => 'UserController@delete',
    'api/servers' => 'ApiController@servers',
    'api/organizations' => 'ApiController@organizations',
    'api/resources' => 'ApiController@resources',
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