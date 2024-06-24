<?php

namespace Riotoon\Repository;

use Riotoon\DbConnexion;
use Riotoon\Entity\Genre;
use Riotoon\Service\BuildErrors;

class GenreRepository extends Genre implements AbstractRepository
{
    private \PDO $connection;
    private $items;

    public function __construct()
    {
        $this->connection = DbConnexion::connection();
    }

    public function setLabel(string $label): Genre
    {
        $statement = $this->connection->prepare('SELECT * FROM genre WHERE label = :lab');
        $statement->bindValue(':lab', clean($label));
        $statement->execute();
        $is_genre = $statement->rowCount();
        if ($is_genre != 0)
            BuildErrors::setErrors('label', 'Ce genre existe déjà');
        return parent::setLabel(clean($label));
    }

    public function add()
    {
        try {
            $query = $this->connection->prepare("INSERT INTO genre(label) VALUES(:lab)");
            $query->bindValue(':lab', parent::getLabel());
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'insertion : " . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $query = $this->connection->prepare("UPDATE genre SET label = :lab WHERE id = :id");
            $query->bindValue(':tit', parent::getLabel());
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
            $query = $this->connection->prepare('DELETE FROM genre WHERE id = :id');
            $query->bindValue(':id', clean($id));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de suppression du genre : " . $e->getMessage());
        }
    }

    public function findAll() {
        try {
            $query = $this->connection->query("SELECT * FROM genre ORDER BY label ASC");
            $this->items = $query->fetchAll(\PDO::FETCH_CLASS, Genre::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $this->items;
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

    public function deleteWebtoonGenre($webtoon) {
        try {
            $query = $this->connection->prepare('DELETE FROM webtoon_genres WHERE webtoon = :web');
            $query->bindValue(':web', clean($webtoon));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de suppression des genres du webtoon : " . $e->getMessage());
        }
    }

    public function fetchOne(string $field, $value)
    {
        try {
            $query = $this->connection->prepare("SELECT * FROM genre
                WHERE id = :id AND label = :lab");
            $query->bindValue('id', clean($value));
            $query->bindValue('lab', clean($field));
            $query->execute();
            $query->setFetchMode(\PDO::FETCH_CLASS, Genre::class);
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible d'éxecuter la requête" . $e->getMessage());
        }

        return $this->items;
    }

    public function find($id) {
        try {
            $query = $this->connection->prepare("SELECT * FROM genre
                WHERE id = :id");
            $query->bindValue('id', clean($id));
            $query->execute();
            $query->setFetchMode(\PDO::FETCH_CLASS, Genre::class);
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible d'éxecuter la requête" . $e->getMessage());
        }

        return $this->items;
    }

    public function getAllGenresWithWebtoonCount() {
        try {
            $query = $this->connection->query("SELECT g.*, COUNT(wg.webtoon) AS webtoon_count
                FROM genre g
                LEFT JOIN webtoon_genres wg ON g.id = wg.genre
                GROUP BY g.id, g.label
                ORDER BY g.id");
            $this->items = $query->fetchAll(\PDO::FETCH_CLASS, Genre::class);
        } catch (\PDOException $e) {
            die("Erreur lors de la récupération des genres avec le nombre de Webtoons : " . $e->getMessage());
        }

        return $this->items;
    }
}