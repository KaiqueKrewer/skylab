<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class JogoController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function listar(Request $request, Response $response)
    {
        $stmt = $this->db->query('SELECT * FROM jogos');
        $jogos = $stmt->fetchAll();

        $response->getBody()->write(json_encode($jogos));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function mostrar(Request $request, Response $response, $args)
    {
        $id = (int) $args['id'];
        $stmt = $this->db->prepare('SELECT * FROM jogos WHERE id = ?');
        $stmt->execute([$id]);
        $jogo = $stmt->fetch();

        if (!$jogo) {
            $response->getBody()->write(json_encode(['error' => 'Jogo não encontrado']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($jogo));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function cadastrar(Request $request, Response $response)
{
    $body = (string) $request->getBody();
    $data = json_decode($body, true);

    if (!$data) {
        $response->getBody()->write(json_encode(['error' => 'JSON inválido ou vazio']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $sql = 'INSERT INTO jogos (nome, plataforma, status) VALUES (?, ?, ?)';
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        $data['nome'],
        $data['plataforma'],
        $data['status'],
    ]);
    $data['id'] = $this->db->lastInsertId();

    $response->getBody()->write(json_encode($data));
    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
}

    public function atualizar(Request $request, Response $response, $args)
    {
        $id = (int) $args['id'];
        $data = json_decode($request->getBody(), true);

        $sql = 'UPDATE jogos SET nome = ?, plataforma = ?, status = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nome'],
            $data['plataforma'],
            $data['status'],
            $id
        ]);

        $response->getBody()->write(json_encode(['message' => 'Jogo atualizado']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deletar(Request $request, Response $response, $args)
    {
        $id = (int) $args['id'];
        $stmt = $this->db->prepare('DELETE FROM jogos WHERE id = ?');
        $stmt->execute([$id]);

        $response->getBody()->write(json_encode(['message' => 'Jogo deletado']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function estatisticas(Request $request, Response $response): Response
    {
        $pdo = $this->db;

        $stmtTempo = $pdo->query("SELECT SUM(tempo_jogado) AS tempo_total FROM jogos");
        $tempoTotal = $stmtTempo->fetch();

        $anoAtual = date('Y');
        $stmtFreq = $pdo->prepare("
            SELECT MONTH(ultimo_acesso) AS mes, COUNT(*) AS acessos
            FROM jogos
            WHERE YEAR(ultimo_acesso) = :ano
            GROUP BY MONTH(ultimo_acesso)
            ORDER BY mes ASC
        ");
        $stmtFreq->execute([':ano' => $anoAtual]);
        $frequenciaMensal = $stmtFreq->fetchAll();

        $stmtGenero = $pdo->query("
            SELECT genero, SUM(tempo_jogado) AS tempo_total
            FROM jogos
            GROUP BY genero
            ORDER BY tempo_total DESC
        ");
        $generosMaisJogados = $stmtGenero->fetchAll();

        $dados = [
            'tempo_total_jogado' => (float) ($tempoTotal['tempo_total'] ?? 0),
            'frequencia_mensal_entrada' => $frequenciaMensal,
            'generos_mais_jogados' => $generosMaisJogados
        ];

        $response->getBody()->write(json_encode($dados));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function registrarAcesso(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);

        if ($id <= 0) {
            $response->getBody()->write(json_encode(['erro' => 'ID inválido']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $pdo = $this->db;
        $stmt = $pdo->prepare("UPDATE jogos SET ultimo_acesso = NOW() WHERE id = :id");
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            $response->getBody()->write(json_encode(['erro' => 'Jogo não encontrado']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode(['mensagem' => 'Acesso registrado com sucesso']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
