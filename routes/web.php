<?php

use App\Routing\Router;
use App\Controller\ProductController;

$router = new Router();

$router->get('/', [ProductController::class, 'show']);

$router->run();

