<?php

namespace Riotoon\Repository;

use Riotoon\DbConnexion;
use Riotoon\Entity\Genre;

class GenreRepository extends Genre
{
    public static function findAll() {
        $connec = DbConnexion::connection();
        try {
            $query = $connec->query("SELECT * FROM genre");
            $items = $query->fetchAll(\PDO::FETCH_CLASS, Genre::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $items;
    }
}