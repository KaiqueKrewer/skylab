<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

// Se você estiver usando um container, inclua aqui
$container = require __DIR__ . '/../container.php';
AppFactory::setContainer($container);

$app = AppFactory::create();
$app->setBasePath('/inventario-jogos/skylab/public');

// Registra as rotas
(require __DIR__ . '/../src/routes/api.php')($app);

// Executa a aplicação
$app->run();