<?php

namespace Riotoon\Repository;

use Riotoon\Entity\Chapter;
use Riotoon\Service\DbConnection;

class ChapterRepository
{
    private \PDO $connec;

    public function __construct() {
        $this->connec = DbConnection::GetConnection();
    }

    public function new(Chapter $chapter) {
        try {            
            $query = $this->connec->prepare("INSERT INTO chapter(ch_num, ch_path, webtoon)
            VALUES(:num, :pat, :web)");
            $query->bindValue(':num', $chapter->getNumber());
            $query->bindValue(':pat', $chapter->getPath());
            $query->bindValue(':web', $chapter->getWebtoon());

            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'insertion : " . $e->getMessage());
        }
    }

    public function isChapter(Chapter $ch): bool
    {
        try {
            $q = $this->connec->prepare("SELECT * FROM chapter WHERE  ch_num = :num AND webtoon = :web");
            $q->bindValue(":num", $ch->getNumber());
            $q->bindValue(":web", $ch->getWebtoon());
            $q->execute();
            return $q->rowCount() > 0;
        } catch (\PDOException $e) {
            die("Vérificaiton du chapitre échoué {$e->getMessage()}");
        }
    }

    public static function findWebtoon(int $webtoon)
    {
        $connection = DbConnection::GetConnection();
        try {
            $query = $connection->prepare("SELECT * FROM chapter
                    WHERE webtoon = :web ORDER BY ch_num DESC");
            $query->bindValue(':web', clean($webtoon));
            $query->execute();
            $items = $query->fetchAll(\PDO::FETCH_CLASS, Chapter::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les informations : " . $e->getMessage());
        }

        return $items;
    }

    public static function fetchOne(string $field, $value)
    {
        $connection = DbConnection::GetConnection();
        try {
            $query = $connection->prepare("SELECT chapter.* FROM chapter
                JOIN webtoon ON webtoon.id = chapter.webtoon
                WHERE webtoon.id = :web AND ch_num = :ch");
            $query->bindValue('web', clean($value));
            $query->bindValue('ch', clean($field));
            $query->execute();
            $query->setFetchMode(\PDO::FETCH_CLASS, Chapter::class);
            $items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible d'éxecuter la requête" . $e->getMessage());
        }

        return $items;
    }

    public function findAll()
    {
        try {
            $query = $this->connec->query("SELECT * FROM chapter");
            $items = $query->fetchAll(\PDO::FETCH_CLASS, Chapter::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $items;
    }
}
