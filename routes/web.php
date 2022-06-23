<?php


use App\Routing\RouteCollection;

use App\Controller\ProductController;
use App\Service\ProductService;
use App\Model\Product;
use App\Database\MysqlDatabase;

$route = new RouteCollection();

$db = new MysqlDatabase();
$model = new Product($db);
$service = new ProductService($model);
$controller = new ProductController($service);

$route->get('/', [$controller, 'show']);

$route->get('/add', [$controller, 'showStoreForm']);

$route->post('/add', [$controller, 'store']);

return $route;
