<?php

use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Injector\Injector;

$definitions = require_once __DIR__.'/definitions.php';

$config = ContainerConfig::create()
    ->withDefinitions($definitions);

$container = new Container($config);

return new Injector($container);
