<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class JogoController {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function listar(Request $request, Response $response) {
        $stmt = $this->db->query('SELECT * FROM jogos');
        $response->getBody()->write(json_encode($stmt->fetchAll()));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function mostrar(Request $request, Response $response, $args) {
        $stmt = $this->db->prepare('SELECT * FROM jogos WHERE id = ?');
        $stmt->execute([$args['id']]);
        $jogo = $stmt->fetch();
        if (!$jogo) return $response->withStatus(404);
        $response->getBody()->write(json_encode($jogo));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function cadastrar(Request $request, Response $response) {
        $data = json_decode($request->getBody(), true);
        $stmt = $this->db->prepare('INSERT INTO jogos (nome, plataforma, status) VALUES (?, ?, ?)');
        $stmt->execute([$data['nome'], $data['plataforma'], $data['status']]);
        $data['id'] = $this->db->lastInsertId();
        $response->getBody()->write(json_encode($data));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function atualizar(Request $request, Response $response, $args) {
        $data = json_decode($request->getBody(), true);
        $stmt = $this->db->prepare('UPDATE jogos SET nome = ?, plataforma = ?, status = ? WHERE id = ?');
        $stmt->execute([$data['nome'], $data['plataforma'], $data['status'], $args['id']]);
        $response->getBody()->write(json_encode(['mensagem' => 'Atualizado']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deletar(Request $request, Response $response, $args) {
        $stmt = $this->db->prepare('DELETE FROM jogos WHERE id = ?');
        $stmt->execute([$args['id']]);
        $response->getBody()->write(json_encode(['mensagem' => 'Deletado']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}