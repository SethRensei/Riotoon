<?php

namespace Riotoon\Repository;

use Riotoon\DbConnexion;
use Riotoon\Entity\Webtoon;

class WebtoonRepository extends Webtoon
{
    private \PDO $connection;

    private $items;

    public function __construct()
    {
        $this->connection = DbConnexion::connection();
    }

    public function add()
    {
        try {
            $query = $this->connection->prepare("INSERT INTO webtoon(title, author, synopsis, cover, release_year, status)
            VALUES(:tit, :aut, :syn, :cov, :rel, :sta)");
            $query->bindValue(':tit', parent::getTitle());
            $query->bindValue(':aut', parent::getAuthor());
            $query->bindValue(':syn', parent::getSynopsis());
            $query->bindValue(':cov', parent::getCover());
            $query->bindValue(':rel', parent::getReleaseYear());
            $query->bindValue(':sta', parent::getStatus());

            $query->execute();
            $last_id = $this->connection->lastInsertId();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'insertion : " . $e->getMessage());
        }

        return $last_id;
    }

    public function edit($id)
    {
        try {
            $query = $this->connection->prepare("UPDATE webtoon SET title = :tit, author = :aut, synopsis = :syn, cover = :cov,
            release_year = :rel, status = :sta, modified_at = CURRENT_TIMESTAMP
            WHERE id = :id");
            $query->bindValue(':tit', parent::getTitle());
            $query->bindValue(':aut', parent::getAuthor());
            $query->bindValue(':syn', parent::getSynopsis());
            $query->bindValue(':cov', parent::getCover());
            $query->bindValue(':rel', parent::getReleaseYear());
            $query->bindValue(':sta', parent::getStatus());
            $query->bindValue(':id', $id);

            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de la mise à jour : " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $query = $this->connection->prepare('DELETE FROM webtoon WHERE id = :id');
            $query->bindValue(':id', clean($id));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de suppression des genres du webtoon : " . $e->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $query = $this->connection->query("SELECT webtoon.*, GROUP_CONCAT(label) AS genres
                    FROM webtoon
                    JOIN webtoon_genres ON webtoon.id = webtoon_genres.webtoon
                    JOIN genre ON genre.id = webtoon_genres.genre
                    GROUP BY webtoon.id;");
            $this->items = $query->fetchAll(\PDO::FETCH_CLASS, Webtoon::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $this->items;
    }

    public function fetchOne(string $field, $value)
    {
        $q = "SELECT webtoon.*, GROUP_CONCAT(genre) AS id_genres, GROUP_CONCAT(label) AS genres
            FROM webtoon
            JOIN webtoon_genres ON webtoon.id = webtoon_genres.webtoon
            JOIN genre ON genre.id = webtoon_genres.genre
            WHERE webtoon.{$field} = " . $value . " 
            GROUP BY webtoon.id";
        try {
            $query = $this->connection->query($q);
            $query->setFetchMode(\PDO::FETCH_CLASS, Webtoon::class);
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible d'éxecuter la requête" . $e->getMessage());
        }

        return $this->items;
    }
}