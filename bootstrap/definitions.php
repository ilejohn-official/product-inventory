<?php
use App\Interface\DatabaseInterface;
use App\Interface\ProductServiceInterface;
use App\Interface\RequestInterface;
use App\Interface\RouteCollectionInterface;
use App\Model\Model;
use App\Model\Product;
use App\Database\MysqlDatabase;
use App\Service\ProductService;
use App\Routing\RouteCollection;
use App\Request\Request;
use App\Request\CreateProductRequest;

[
'host' => $host, 
'database' => $dbName, 
'username'=> $username, 
'password'=>$password,
'charset'=>$charset
] = require __DIR__."../../config/db.php";

 return [
    ProductServiceInterface::class => ProductService::class,
    RouteCollectionInterface::class => RouteCollection::class,
    Model::class => Product::class,
    RequestInterface::class => Request::class,
    DatabaseInterface::class => [
        'class' => MysqlDatabase::class,
        '__construct()' => [$host, $dbName, $username, $password, $charset]
    ]
];
