<?php

require __DIR__.'/vendor/autoload.php';

$injector = require_once __DIR__.'/bootstrap/app.php';

$response = $injector->make(App\Routing\Router::class)(
    require_once __DIR__.'/routes/web.php'
);

$injector->invoke($response);
