<?php

use App\Request\Request;
use App\Routing\Router;
use App\Controller\ProductController;


$router = new Router(new Request());

$router->get('/', [ProductController::class, 'show']);

$router->get('/add', [ProductController::class, 'showStoreForm']);

$router->post('/add', [ProductController::class, 'store']);

$router->process();