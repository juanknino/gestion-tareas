<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Cargar rutas
$routes = require __DIR__ . '/../routes/web.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Limpiar la URI para evitar problemas con parámetros
$requestUri = strtok($requestUri, '?');

// Buscar y ejecutar la ruta correspondiente
foreach ($routes as $route => $action) {
    [$method, $path] = explode(' ', $route);

    if ($method === $requestMethod && preg_match('#^' . $path . '$#', $requestUri, $matches)) {
        [$controller, $method] = $action;
        (new $controller)->$method(...array_slice($matches, 1));
        exit;
    }
}

http_response_code(404);
echo json_encode(["message" => "Route not found!"]);
