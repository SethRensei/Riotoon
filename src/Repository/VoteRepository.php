<?php

namespace Riotoon\Repository;

use Riotoon\Service\DbConnection;

class VoteRepository
{
    private $connec;

    private $former_vote;

    public function __construct()
    {
        $this->connec = DbConnection::GetConnection();
    }

    private function recordExists(int $webtoon)
    {
        $query = $this->connec->prepare("SELECT title, author FROM webtoon WHERE id = :web");
        $query->bindValue(':web', $webtoon);
        $query->execute();
        if ($query->rowCount() == 0) {
            throw new \Exception("Impossible de voter pour un enregistrement qui n'existe pas");
        }
    }

    public function like(int $webtoon, $user)
    {
        if ($this->vote($webtoon, $user, 1)) {
            $sql_part = "";
            if ($this->former_vote) {
                $sql_part = ", dislikes = dislikes - 1";
            }
            $this->connec->query("UPDATE webtoon SET likes = likes + 1 $sql_part WHERE id = {$webtoon}");
            return true;
        } else {
            $this->connec->query("UPDATE webtoon SET likes = likes - 1 WHERE id = {$webtoon}");
        }
        return false;
    }

    public function dislike(int $webtoon, $user)
    {
        if ($this->vote($webtoon, $user, -1)) {
            $sql_part = "";
            if ($this->former_vote) {
                $sql_part = ", likes = likes - 1";
            }
            $this->connec->query("UPDATE webtoon SET dislikes = dislikes + 1 $sql_part WHERE id = {$webtoon}");
            return true;
        } else {
            $this->connec->query("UPDATE webtoon SET dislikes = dislikes - 1 WHERE id = {$webtoon}");
        }
        return false;
    }

    private function vote(int $webtoon, $user, int $vote)
    {
        $this->recordExists($webtoon);

        $query = $this->connec->prepare('SELECT id, vote FROM vote WHERE webtoon = :web AND user = :user');
        $query->bindValue(':web', $webtoon);
        $query->bindValue(':user', $user);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_OBJ);
        $vote_row = $query->fetch();

        if ($vote_row) {
            if ($vote_row->vote == $vote) {
                $this->connec->query('DELETE FROM vote WHERE id = ' . $vote_row->id);
                return false;
            }
            $this->former_vote = $vote_row;
            $query = $this->connec->prepare("UPDATE vote SET vote = ? WHERE id = {$vote_row->id}")->execute([$vote]);
            return true;
        }
        $query = $this->connec->prepare('INSERT INTO vote(webtoon, user, vote) VALUES(:web, :user, :vot)');
        $query->bindValue(':web', $webtoon);
        $query->bindValue(':user', $user);
        $query->bindValue(':vot', $vote);
        $query->execute();
        return true;
    }

    public function updateCount(int $webtoon)
    {
        $query = $this->connec->prepare("SELECT COUNT(id) as count, vote FROM vote WHERE webtoon = :web GROUP BY vote");
        $query->bindValue(':web', $webtoon);
        $query->execute();
        $votes = $query->fetchAll(\PDO::FETCH_OBJ);
        $counts = ['-1' => 0, '1' => 0];
        foreach ($votes as $vote) {
            $counts[$vote->vote] = $vote->count;
        }
        $query = $this->connec->query("UPDATE webtoon SET likes = {$counts[1]}, dislikes = {$counts[-1]} WHERE id = {$webtoon}");
        return true;
    }

    /**
     * Permet d'ajouter une classe is-like ou is-dislike
     * 
     */
    public function getClass($webtoon, $user)
    {
        $query = $this->connec->prepare('SELECT * FROM vote WHERE user = :user AND webtoon = :web');
        $query->bindValue(':web', $webtoon);
        $query->bindValue(':user', $user);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_OBJ);
        $vote = $query->fetch();
        if ($vote)
            return $vote->vote == 1 ? 'is-like' : 'is-dislike';
        return null;
    }
}
