<?php
use Slim\App;
use App\Controllers\JogoController;

return function (App $app) {
    $app->get('/', function ($req, $res) {
    $res->getBody()->write("API rodando!");
    return $res;
});
    $app->get('/jogos', [JogoController::class, 'listar']);
    $app->get('/jogos/{id}', [JogoController::class, 'mostrar']);
    $app->post('/jogos', [JogoController::class, 'cadastrar']);
    $app->put('/jogos/{id}', [JogoController::class, 'atualizar']);
    $app->delete('/jogos/{id}', [JogoController::class, 'deletar']);
};