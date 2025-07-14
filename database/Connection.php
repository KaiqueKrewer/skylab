<?php
namespace App\Database;

use PDO;
use PDOException;

class Connection {
    public function get() {
        try {
            return new PDO('mysql:host=localhost;dbname=inventario;charset=utf8', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die('Erro na conexão: ' . $e->getMessage());
        }
    }
}