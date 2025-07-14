<?php
namespace App\db;

use PDO;
use PDOException;

class Connection
{
    public static function getConnection(): PDO
    {
        $host = 'localhost';
        $db   = 'inventario'; 
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';
            
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            die('Erro na conexão: ' . $e->getMessage());
        }
    }
}
