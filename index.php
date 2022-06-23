<?php

require __DIR__.'/vendor/autoload.php';

use App\Routing\Router;
use App\Request\Request;

$route = require __DIR__.'/routes/web.php';


$request = new Request();
$router = new Router($request);

$router($route);
