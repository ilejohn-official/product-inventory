<?php


use App\Routing\RouteCollection;
use App\Controller\ProductController;

$route = new RouteCollection();

$controller = $injector->make(ProductController::class);

$route->get('/', [$controller, 'show']);

$route->get('/products', [$controller, 'getProducts']);

$route->get('/add-product', [$controller, 'showStoreForm']);

$route->post('/products', [$controller, 'store']);

$route->post('/delete-products', [$controller, 'delete']);

return $route;
