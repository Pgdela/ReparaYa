<?php

$routes = require __DIR__ . '/routes/web.php';

$page = $_GET['page'] ?? 'home';

if (!isset($routes[$page])) {

    http_response_code(404);
    echo "Página no encontrada";
    exit;

}

$route = $routes[$page];

$controllerName = $route['controller'];
$method = $route['method'];

require __DIR__ . '/app/controllers/' . $controllerName . '.php';

$controller = new $controllerName();

$controller->$method();