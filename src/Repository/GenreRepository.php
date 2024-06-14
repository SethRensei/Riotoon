<?php

namespace Riotoon\Repository;

use Riotoon\DbConnexion;
use Riotoon\Entity\Genre;

class GenreRepository extends Genre
{
    private \PDO $connection;

    public function __construct()
    {
        $this->connection = DbConnexion::connection();
    }

    public function findAll() {
        try {
            $query = $this->connection->query("SELECT * FROM genre ORDER BY label ASC");
            $items = $query->fetchAll(\PDO::FETCH_CLASS, Genre::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $items;
    }

    public function addWebtoonGenre($webtoon, $genre) {
        try {
            $query = $this->connection->prepare('INSERT INTO webtoon_genres(webtoon, genre)
            VALUES(:web, :gen)');
            $query->bindValue(':web', clean($webtoon));
            $query->bindValue(':gen', clean($genre));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'insertion : " . $e->getMessage());
        }
    }
}