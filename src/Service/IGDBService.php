<?php
namespace App\Services;

use GuzzleHttp\Client;

class IGDBService
{
    private $client;
    private $clientId;
    private $accessToken;

    public function __construct()
{
    $this->clientId = 'g58bx738bt8xj2fyvhafm2796gv0yp';
    $this->clientSecret = 'muju1mqpc7s1wiibu7u58yqrj01a30'; // Substitua

    $this->accessToken = $this->obterAccessToken();

    $this->client = new Client([
        'base_uri' => 'https://api.igdb.com/v4/',
        'headers' => [
            'Client-ID' => $this->clientId,
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ]
    ]);
}
    private function obterAccessToken()
{
    $client = new Client(['base_uri' => 'https://id.twitch.tv/oauth2/']);
    
    $response = $client->post('token', [
        'form_params' => [
            'client_id' => $this->clientId,
            'client_secret' => 'muju1mqpc7s1wiibu7u58yqrj01a30', // Troque por seu secret real
            'grant_type' => 'client_credentials',
        ]
    ]);
    
    $body = json_decode($response->getBody()->getContents(), true);
    return $body['access_token'] ?? null;
}

    public function buscarJogo(string $nome)
    {
        $query = "fields name,genres.name,involved_companies.company.name,cover.url,summary; search \"{$nome}\"; limit 1;";

        $response = $this->client->post('games', [
            'body' => $query
        ]);

        $dados = json_decode($response->getBody()->getContents(), true);
        return $dados;
    }
}
