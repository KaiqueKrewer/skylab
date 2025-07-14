<?php
use Slim\Factory\AppFactory;

$container = require __DIR__ . '/../container.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->setBasePath('/inventario-jogos/public');

// Rotas
(require __DIR__ . '/../src/routes/api.php')($app);

$app->run();