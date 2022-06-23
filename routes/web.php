<?php


use App\Routing\RouteCollection;
use App\Controller\ProductController;

$route = new RouteCollection();

$controller = $injector->make(ProductController::class);

$route->get('/', [$controller, 'show']);

$route->get('/add', [$controller, 'showStoreForm']);

$route->post('/add', [$controller, 'store']);

return $route;
