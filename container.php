<?php
use DI\Container;
use App\Database\Connection;

$container = new Container();
$container->set('db', function () {
    return (new Connection())->get();
});
return $container;