<?php

namespace Riotoon\Repository;

use Riotoon\DbConnexion;
use Riotoon\Entity\Chapter;
use Riotoon\Service\BuildErrors;

class ChapterRepository extends Chapter implements AbstractRepository
{
    private \PDO $connection;

    private $items;

    public function __construct()
    {
        $this->connection = DbConnexion::connection();
    }

    public function setChNum(string $ch_num): Chapter
    {
        parent::setChNum($ch_num);
        $statement = $this->connection->prepare('SELECT * FROM chapter WHERE ch_num = :num AND webtoon = :web');
        $statement->bindValue(':num', clean($ch_num));
        $statement->bindValue(':web', parent::getWebtoon());
        $statement->execute();
        $is_chapter = $statement->rowCount();
        if ($is_chapter != 0)
            BuildErrors::setErrors('exist', 'Ce chapitre existe déjà');
        return parent::setChNum($ch_num);
    }

    public function add()
    {
        try {
            $query = $this->connection->prepare("INSERT INTO chapter(ch_num, ch_path, webtoon)
            VALUES(:num, :pat, :web)");
            $query->bindValue(':num', parent::getChNum());
            $query->bindValue(':pat', parent::getChPath());
            $query->bindValue(':web', parent::getWebtoon());

            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'insertion : " . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $query = $this->connection->prepare("UPDATE chapter SET ch_path = :pat, modified_at = CURRENT_TIMESTAMP
            WHERE id = :id AND webtoon = :web AND ch_num = :num");
            $query->bindValue(':num', parent::getChNum());
            $query->bindValue(':pat', parent::getChPath());
            $query->bindValue(':web', parent::getWebtoon());
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
            $query = $this->connection->prepare('DELETE FROM chapter WHERE id = :id');
            $query->bindValue(':id', clean($id));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de suppression des genres du chapter : " . $e->getMessage());
        }
    }

    public function findWebtoon(int $id)
    {
        try {
            $query = $this->connection->prepare("SELECT * FROM chapter
                    WHERE webtoon = :web ORDER BY ch_num DESC");
            $query->bindValue(':web', clean($id));
            $query->execute();
            $this->items = $query->fetchAll(\PDO::FETCH_CLASS, Chapter::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les informations : " . $e->getMessage());
        }

        return $this->items;
    }

    public function fetchOne(string $field, $value)
    {
        try {
            $query = $this->connection->prepare("SELECT chapter.* FROM chapter
                JOIN webtoon ON webtoon.id = chapter.webtoon
                WHERE webtoon.id = :web AND ch_num = :ch");
            $query->bindValue('web', clean($value));
            $query->bindValue('ch', clean($field));
            $query->execute();
            $query->setFetchMode(\PDO::FETCH_CLASS, Chapter::class);
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible d'éxecuter la requête" . $e->getMessage());
        }

        return $this->items;
    }

    public function findAll()
    {
        try {
            $query = $this->connection->query("SELECT * FROM chapter");
            $this->items = $query->fetchAll(\PDO::FETCH_CLASS, Chapter::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $this->items;
    }
}