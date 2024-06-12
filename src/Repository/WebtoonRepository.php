<?php

namespace Riotoon\Repository;

use Riotoon\DbConnexion;
use Riotoon\Entity\Webtoon;

class WebtoonRepository extends Webtoon
{
    private \PDO $connection;

    public function __construct() {
        $this->connection = DbConnexion::connection();
    }

    public function add() {
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
}