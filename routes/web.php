<?php

use App\Request\Request;
use App\Routing\Router;
use App\Controller\ProductController;
use App\Service\ProductService;
use App\Model\Product;
use App\Database\MysqlDatabase;

$router = new Router(new Request());

$controller = new ProductController(new ProductService(new Product(new MysqlDatabase(Product::$table))));

$router->get('/', [$controller, 'show']);

$router->get('/add', [$controller, 'showStoreForm']);

$router->post('/add', [$controller, 'store']);

$router->process();
