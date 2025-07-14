use DI\Container;

require __DIR__ . '/vendor/autoload.php';

$container = new Container();

// Aqui configure as dependências no container, ex:
$container->set('db', function () {
    return \App\db\Connection::getConnection();
});

return $container;
<?php
// bootstrap.php