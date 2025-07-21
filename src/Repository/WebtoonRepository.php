<?php

namespace Riotoon\Repository;

use Riotoon\Entity\Webtoon;
use Riotoon\Service\DbConnection;

class WebtoonRepository
{
    private \PDO $connec;

    public function __construct() {
        $this->connec = DbConnection::GetConnection();
    }

    public function new(Webtoon $webtoon) {
        $last_id = 0;
        try {
            $q = $this->connec->prepare("INSERT INTO webtoon(title, author, synopsis, cover, status)
            VALUES(:tit, :aut, :syn, :cov, :sta)");
            $q->bindValue(':tit', $webtoon->getTitle(), \PDO::PARAM_STR);
            $q->bindValue(':aut', $webtoon->getAuthor(), \PDO::PARAM_STR);
            $q->bindValue(':syn', $webtoon->getSynopsis(), \PDO::PARAM_STR);
            $q->bindValue(':cov', $webtoon->getCover(), \PDO::PARAM_STR);
            $q->bindValue(':sta', $webtoon->getStatus(), \PDO::PARAM_STR);

            $q->execute();
            $last_id = $this->connec->lastInsertId();
            $q->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenu à l'ajout d'un webtoon : {$e->getMessage()}");
        }

        return $last_id;
    }
}
