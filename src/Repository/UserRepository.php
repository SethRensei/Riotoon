<?php

namespace Riotoon\Repository;

use Riotoon\Entity\Users;
use Riotoon\Service\BuilderError;
use Riotoon\Service\DbConnection;

final class UserRepository extends Users
{
    private \PDO $connec;
    private $items;

    public function __construct() {
        $this->connec = DbConnection::GetConnection();
    }

    public function new(Users $user) {
        try {
                $q = $this->connec->prepare('INSERT INTO users(pseudo, email, password, roles, is_verify, u_token, token_expire)
                VALUES(:pse, :ema, :pass, :rol, :ver, :tok, :tok_ex)');
                $q->bindValue(':pse', $user->getPseudo(), \PDO::PARAM_STR);
                $q->bindValue(':ema', $user->getEmail(), \PDO::PARAM_STR);
                $q->bindValue(':pass', $user->getPassword(), \PDO::PARAM_STR);
                $q->bindValue(':rol', $user->getRoles());
                $q->bindValue(':tok', $user->getToken(), \PDO::PARAM_INT);
                $q->bindValue(':tok_ex', $user->getTokenExpire(), \PDO::PARAM_INT);
                $q->bindValue(':ver', $user->getIsVerify(), \PDO::PARAM_INT);
    
                $q->execute();
                $q->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenu à l'ajout d'un l'utilisateur : {$e->getMessage()}");
        }
    }

    public function find(string $search)
    {
        try {
            $query = $this->connec->prepare('SELECT * FROM users
                WHERE pseudo = :sea OR email = :sea');
            $query->bindValue('sea', clean($search));
            $query->execute();
            $query->setFetchMode(\PDO::FETCH_CLASS, Users::class);
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible d'éxecuter la requête" . $e->getMessage());
        }
        return $this->items;
    }

    public function verifyEmail(Users $u) {
        try {
            $query = $this->connec->prepare('UPDATE users SET is_verify = :ver, updated_at = CURRENT_TIMESTAMP WHERE pseudo = :pse');
            $query->bindValue(':ver', $u->getIsVerify());
            $query->bindValue(':pse', $u->getPseudo());
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Verification du compte échouée : " . $e->getMessage());
        }
    }

    public function changeToken(Users $user)
    {
        try {
            $q = $this->connec->prepare("UPDATE users SET u_token = :tok, token_expire = :tok_ex WHERE pseudo = :pse");
            $q->bindValue(':pse', $user->getPseudo(), \PDO::PARAM_STR);
            $q->bindValue(':tok', $user->getToken(), \PDO::PARAM_INT);
            $q->bindValue(':tok_ex', $user->getTokenExpire(), \PDO::PARAM_INT);
            $q->execute();
        } catch (\PDOException $e) {
            die('Changement de token échoué : '. $e->getMessage());
        }
    }

    public function setPseudo(?string $pseudo): Users
    {
        parent::setPseudo($pseudo);
        if ($this->isUser($pseudo))
            BuilderError::setErrors("exists", "Pseudo ou Email déjà utilisé par un autre utilisateur");
        return parent::setPseudo($pseudo);
    }
    public function setEmail(?string $email): Users
    {
        parent::setEmail($email);
        if ($this->isUser($email))
            BuilderError::setErrors("exists", "Pseudo ou Email déjà utilisé par un autre utilisateur");
        return parent::setEmail($email);
    }

    private function isUser(string $user) : bool
    {
        try {
            $statement = $this->connec->prepare('SELECT * FROM users WHERE pseudo = :user OR email = :user');
            $statement->bindValue(':user', clean($user));
            $statement->execute();
            return $statement->rowCount() > 0;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return true;
    }
}
