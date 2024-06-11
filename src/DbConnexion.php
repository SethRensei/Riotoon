<?php

namespace Riotoon;

use Symfony\Component\Dotenv\Dotenv;

class DbConnexion {

    /**
     * Establishes a database connection using PDO.
     * @return \PDO The PDO database connection object.
     */
    public static function connection(): \PDO
    {
        // Chargement des variables d'environnement depuis le fichier .env
        $dotenv = new Dotenv();
        $dotenv->load(dirname(__DIR__) . '/.env');
        // Récupérer les détails de connexion à la base de données à partir des variables d'environnement
        $host = $_ENV['DB_HOST'];
        $name = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        try {
            $pdo = new \PDO("mysql:host=$host;dbname=$name;charset=utf8", $user, $pass);
            // Définir le mode d'erreur PDO sur les exceptions
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            // Si la connexion échoue, terminez le script et affichez un message d'erreur
            die('Connexion impossible : ' . $e->getMessage() . ' à la ligne : ' . $e->getLine());
        }

        return $pdo;
    }
}