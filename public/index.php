<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$container = require __DIR__ . '/../container.php';
AppFactory::setContainer($container);

$app = AppFactory::create();
$app->setBasePath('/inventario-jogos/skylab');

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);


(require __DIR__ . '/../src/routes/api.php')($app);


$app->run();