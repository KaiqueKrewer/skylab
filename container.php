<?php
require_once __DIR__ . '/vendor/autoload.php';

use DI\Container;
use App\db\Connection;
use App\Controllers\JogoController;

$container = new Container();

// Definição do serviço de banco de dados
$container->set('db', function () {
    return Connection::getConnection();
});

// Injeção manual do controlador
$container->set(JogoController::class, function($c) {
    return new JogoController($c->get('db'));
});

return $container;
