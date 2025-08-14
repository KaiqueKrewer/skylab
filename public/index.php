<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use App\Services\IGDBService;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$app->setBasePath('/skylab/public');
$app->addRoutingMiddleware();

$app->get('/', function ($request, $response, $args) {
    $response->getBody()->write("Hello, Skylab!");
    return $response;
});

$app->get('/public', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response) {
    // Example of using IGDBService
    $igdbService = new IGDBService();
    $games = $igdbService->getGames();
    // You can process $games and return a response
    $response->getBody()->write(json_encode($games));
    return $response->withHeader('Content-Type', 'application/json');

 });

$app->run();
