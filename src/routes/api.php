<?php
use Slim\App;
use App\Controllers\JogoController;

return function (App $app) {

    // Rota básica para ver se a API está rodando
    $app->get('/', function ($request, $response) {
        $response->getBody()->write("API rodando!");
        return $response;
    });

    // CRUD de jogos
    $app->get('/jogos', [JogoController::class, 'listar']);
    $app->get('/jogos/{id}', [JogoController::class, 'mostrar']);
    $app->post('/jogos', [JogoController::class, 'cadastrar']);
    $app->put('/jogos/{id}', [JogoController::class, 'atualizar']);
    $app->delete('/jogos/{id}', [JogoController::class, 'deletar']);

    // Estatísticas e registro de acesso
    $app->get('/estatisticas', [JogoController::class, 'estatisticas']);
    $app->post('/jogos/{id}/acesso', [JogoController::class, 'registrarAcesso']);
};
