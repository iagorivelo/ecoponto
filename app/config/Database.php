<?php

namespace App\config;

use PDO;
use PDOException;

class Database
{
    /**
     * Estabelece e retorna a conexão PDO, lendo as credenciais do arquivo .env.
     * @return PDO O objeto de conexão ativo.
     * @throws PDOException Se a conexão falhar.
     */
    public static function connect(): PDO
    {
        $db_host = $_ENV['DB_HOST'];
        $db_name = $_ENV['DB_DATABASE'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ];

        $dsn = "mysql:dbname={$db_name};host={$db_host}";
        try {
            $pdo = new PDO($dsn, $username, $password, $options);
            return $pdo;
        } catch (PDOException $e) {
            throw new PDOException("Erro ao conectar ao banco de dados: " . $e->getMessage(), (int)$e->getCode());
        }
    }
}
