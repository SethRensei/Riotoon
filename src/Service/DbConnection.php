<?php

namespace Riotoon\Service;

class DbConnection
{
    /**
     * Establishes a database connection using PDO.
     * @return \PDO The PDO database connection object.
     */
    public static function GetConnection(): \PDO
    {
        $host = $_ENV['DB_HOST'];
        // $port = $_ENV['DB_PORT'];
        $name = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        try {
            $pdo = new \PDO("mysql:host=$host;dbname=$name;charset=utf8", $user, $pass);
            // Using PostgreSQL
            // $pdo = new \PDO("pgsql:host=$host;port=$port;dbname=$name", $user, $pass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die('Connection impossible : ' . $e->getMessage() . ' to the ligne : ' . $e->getLine());
        }
        return $pdo;
    }
}
