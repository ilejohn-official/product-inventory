<?php

require __DIR__.'/vendor/autoload.php';

use App\Routing\Router;

$injector = require_once __DIR__.'/bootstrap/app.php';

$response = $injector->make(Router::class)(
    require_once __DIR__.'/routes/web.php'
);

$injector->invoke($response);
