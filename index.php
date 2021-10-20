<?php
session_start();
require_once 'vendor/autoload.php';

use App\Middlewares\AuthorizedMiddleware;
use App\View;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use App\TwigRenderer;



$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/product/index', 'ProductsController@index');
    $r->addRoute('GET', '/product/details/{id:\d+}', 'ProductsController@details');
    $r->addRoute('GET', '/product/create', 'ProductsController@create');
    $r->addRoute('POST', '/product/insert', 'ProductsController@insert');
    $r->addRoute('GET', '/product/delete/{id:\d+}', 'ProductsController@delete');
    $r->addRoute('GET', '/product/edit/{id:\d+}', 'ProductsController@edit');
    $r->addRoute('POST', '/product/update/{id:\d+}', 'ProductsController@update');
    $r->addRoute('GET', '/home/login', 'UserController@login');
    $r->addRoute('POST', '/home/authenticate', 'UserController@authenticate');
    $r->addRoute('GET', '/home/logout', 'UserController@logout');
    $r->addRoute('GET', '/home/products', 'ProductsController@indexByUserId');
    $r->addRoute('GET', '/home/products/details/{id:\d+}', 'ProductsController@detailsByUser');
    $r->addRoute('GET', '/home/registration', 'UserController@registration');
    $r->addRoute('POST', '/home/register', 'UserController@register');

});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:

        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:

        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $middlewares = [

            'ProductsController@insert' => [
                AuthorizedMiddleware::class
            ],
            'ProductsController@create' => [
                AuthorizedMiddleware::class
            ],
            'ProductsController@delete' => [
                AuthorizedMiddleware::class
            ],
            'ProductsController@edit' => [
                AuthorizedMiddleware::class
            ],
            'ProductsController@update' => [
                AuthorizedMiddleware::class
            ],
            'ProductsController@indexByUser' => [
                AuthorizedMiddleware::class
            ],
            'ProductsController@detailsByUser' => [
                AuthorizedMiddleware::class
            ]

        ];


        if(array_key_exists($handler, $middlewares)){
            foreach ($middlewares[$handler] as $middleware){
                (new $middleware())->handle();
            }
        }

        [$controller, $method] = explode('@', $handler);
        $controller = 'App\Controllers\\' . $controller;
        $controller = new $controller();
        $response = $controller->$method($vars['id']);
        if($response instanceof View){
            TwigRenderer::render($response->getPath(), $response->getVars());
        }
        break;
}
