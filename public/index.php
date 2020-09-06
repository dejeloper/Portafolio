<?php

ini_set('display_errors', 1);
ini_set('display_starup_error', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

session_start();

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use Zend\Diactoros\Response\RedirectResponse;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'portafolio',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
 
$capsule->setAsGlobal(); 
$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
); 

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

$map->get('index', '/Portafolio/', [
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction'
]);

$map->get('page404', '/Portafolio/notfound', [
    'controller' => 'App\Controllers\IndexController',
    'action' => 'page404Action'
]); 

$map->get('loginForm', '/Portafolio/login', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLoginAction'
]); 

$map->get('logoutForm', '/Portafolio/logout', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogoutAction'
]);

$map->post('auth', '/Portafolio/auth', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'authAction'
]); 

$map->get('admin', '/Portafolio/admin', [
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getIndex', 
    'auth' => true 
]); 

$map->get('addJobs', '/Portafolio/jobs/add', [
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction', 
    'auth' => true
]);

$map->post('saveJobs', '/Portafolio/jobs/add', [
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction', 
    'auth' => true
]);

$map->get('addProjects', '/Portafolio/projects/add', [
    'controller' => 'App\Controllers\ProjectsController',
    'action' => 'getAddProjectAction', 
    'auth' => true
]);

$map->post('saveProjects', '/Portafolio/projects/add', [
    'controller' => 'App\Controllers\ProjectsController',
    'action' => 'getAddProjectAction', 
    'auth' => true
]); 

$map->get('addUsers', '/Portafolio/users/add', [
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getAddUserAction', 
    'auth' => true
]);

$map->post('saveUsers', '/Portafolio/users/add', [
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getAddUserAction', 
    'auth' => true
]); 
 
$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if (!$route){
    $controllerName = 'App\Controllers\IndexController';
    $actionName = 'page404Action';
} else { 
    $handlerData = $route->handler;
    $needsAuth = $handlerData['auth'] ?? false;
    $idusuario = $_SESSION['userId'] ?? null; 
    
    if ($needsAuth && !$idusuario) {
        $controllerName = 'App\Controllers\AuthController';
        $actionName = 'getLogoutAction';
    } else {
        if ($handlerData['action'] == 'getLoginAction'){
            $controllerName = 'App\Controllers\AdminController';
            $actionName = 'getIndex';
        } else{
            $controllerName = $handlerData['controller'];
            $actionName = $handlerData['action'];
        }
    }
}
 
$controller = new $controllerName;
$response = $controller->$actionName($request);


foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
http_response_code($response->getStatusCode());
echo $response->getBody();


// $route = $_GET['route'] ?? '/';
// if ($route == '/'){
//     require '../index.php';
// } elseif($route == 'addJob'){
//     require '../addJob.php';
// } elseif($route == 'addProject'){
//     require '../addProject.php';
// } else{
//     require '../index.php';
// }