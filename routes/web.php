<?php

use App\Request\Request;
use App\Routing\Router;
use App\Controller\ProductController;
use App\Service\ProductService;
use App\Model\Product;
use App\Database\MysqlDatabase;

$request = new Request();
$router = new Router($request);

$db = new MysqlDatabase();
$model = new Product($db);
$service = new ProductService($model);
$controller = new ProductController($service);

$router->get('/', [$controller, 'show']);

$router->get('/add', [$controller, 'showStoreForm']);

$router->post('/add', [$controller, 'store']);

$router->process();
