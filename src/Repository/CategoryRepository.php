<?php

namespace Riotoon\Repository;
use Riotoon\Entity\Category;
use Riotoon\Service\DbConnection;

class CategoryRepository extends Category
{
    private static \PDO $connec;

    public static function init() {
        if (!isset(self::$connec)) {
            self::$connec = DbConnection::GetConnection();
        }
    }

    public static function add(string $name) {
        self::init();
        try {
            $query = self::$connec->prepare("INSERT INTO category(label) VALUES(:lab)");
            $query->bindValue(':lab', $name);
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'insertion : " . $e->getMessage());
        }
    }

    public static function delete($id)
    {
        self::init();
        try {
            $query = self::$connec->prepare('DELETE FROM category WHERE c_id = :id');
            $query->bindValue(':id', clean($id));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de suppression du genre : " . $e->getMessage());
        }
    }

    public static function getById($id)
    {
        self::init();
        try {
            $query = self::$connec->prepare("SELECT * FROM category WHERE id=:id");
            $query->bindValue(":id", $id);
            $query->execute();
            $query->setFetchMode(\PDO::FETCH_CLASS, Category::class);
            $category = $query->fetch();
            return $category;
        } catch (\PDOException $e) {
            die("Récupération impossible : ". $e->getMessage());
        }
    }
 
    public static function isCategory($name)
    {
        self::init();
        try {
            $query = self::$connec->prepare("SELECT * FROM category WHERE label = :name");
            $query->bindValue(":name", $name);
            $query->execute();
            return $query->rowCount() > 0;
        } catch (\PDOException $e) {
            die("Vérificaiton du genre échoué {$e->getMessage()}");
        }
    }

    public static function findAll()
    {
        self::init();
        try {
            $query = self::$connec->query("SELECT * FROM category ORDER BY label ASC");
            $items = $query->fetchAll(\PDO::FETCH_CLASS, Category::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $items;
    }

    public static function addCategoriesForWebtoon($webtoon, $category)
    {
        self::init();
        try {
            $query = self::$connec->prepare('INSERT INTO web_cat(webtoon, category) VALUES(:web, :cat)');
            $query->bindValue(':web', clean($webtoon));
            $query->bindValue(':cat', clean($category));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'insertion : " . $e->getMessage());
        }
    }

    public static function deleteCategorieForWebtoon($webtoon)
    {
        self::init();
        try {
            $query = self::$connec->prepare('DELETE FROM web_cat WHERE webtoon = :web');
            $query->bindValue(':web', clean($webtoon));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de suppression des genres du webtoon : " . $e->getMessage());
        }
    }

    public static function getCategoriesWithWebtoonCount()
    {
        self::init();
        try {
            $query = self::$connec->query("SELECT c.*, COUNT(wc.webtoon) AS webtoon_count
                FROM category c
                LEFT JOIN web_cat wc ON c.id = wc.category
                GROUP BY c.id, c.label
                ORDER BY c.label");
            $items = $query->fetchAll(\PDO::FETCH_CLASS, Category::class);
        } catch (\PDOException $e) {
            die("Erreur lors de la récupération des genres avec le nombre de Webtoons : " . $e->getMessage());
        }

        return $items;
    }
}
