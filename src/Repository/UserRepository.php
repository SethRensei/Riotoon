<?php

namespace Riotoon\Repository;

use Riotoon\DbConnexion;
use Riotoon\Entity\User;
use Riotoon\Service\BuildErrors;

class UserRepository extends User implements AbstractRepository
{
    private \PDO $connection;
    private $items;

    public function __construct()
    {
        $this->connection = DbConnexion::connection();
        parent::updateConfirKey();
    }

    public function setPseudo(string $pseudo): User
    {
        parent::setPseudo($pseudo);
        $statement = $this->connection->prepare('SELECT * FROM user WHERE pseudo = :is_user');
        $statement->bindValue(':is_user', clean($pseudo));
        $statement->execute();
        $is_user = $statement->rowCount();
        if ($is_user != 0)
            BuildErrors::setErrors('pseudo', 'Ce pseudo est déjà utilisé');
        return parent::setPseudo($pseudo);
    }
    public function setEmail(string $email): User
    {
        parent::setEmail($email);
        $statement = $this->connection->prepare('SELECT * FROM user WHERE email = :is_user');
        $statement->bindValue(':is_user', clean($email));
        $statement->execute();
        $is_user = $statement->rowCount();
        if ($is_user != 0)
            BuildErrors::setErrors('email', 'Cet email est déjà utilisé');
        return parent::setEmail($email);
    }

    public function add()
    {
        try {
            $query = $this->connection->prepare("INSERT INTO user(pseudo, fullname, email, roles, password, confir_key, is_verified)
            VALUES(:pse, :ful, :mai, :rol, :pas, :con, :ver)");
            $query->bindValue(':pse', parent::getPseudo());
            $query->bindValue(':ful', parent::getFullname());
            $query->bindValue(':rol', parent::getRoles());
            $query->bindValue(':mai', parent::getEmail());
            $query->bindValue(':pas', parent::getPassword());
            $query->bindValue(':con', parent::getConfirKey());
            $query->bindValue(':ver', parent::getIsVerified());
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'insertion : " . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $query = $this->connection->prepare("UPDATE user SET fullname = :ful, roles = :rol, modified_at = CURRENT_TIMESTAMP
            WHERE id = :id");
            $query->bindValue(':ful', parent::getFullname());
            $query->bindValue(':rol', parent::getRoles());
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
            $query = $this->connection->prepare('DELETE FROM user WHERE id = :id');
            $query->bindValue(':id', clean($id));
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'utilisateur : " . $e->getMessage());
        }
    }

    public function addProfilePicture($pseudo) {
        try {
            $query = $this->connection->prepare("UPDATE user SET profile_picture = :pro, modified_at = CURRENT_TIMESTAMP
            WHERE pseudo = :pse");
            $query->bindValue(':pro', parent::getProfilePicture());
            $query->bindValue(':pse', $pseudo);

            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors de l'ajout de la photo de profil : " . $e->getMessage());
        }
    }

    public function editPassword($pseudo) {
        try {
            $query = $this->connection->prepare("UPDATE user SET password = :pas, modified_at = CURRENT_TIMESTAMP
            WHERE pseudo = :pse");
            $query->bindValue(':pas', parent::getPassword());
            $query->bindValue(':pse', $pseudo);

            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Une erreur est survenue lors du changement du mot de passe : " . $e->getMessage());
        }
    }

    public function verify(string $pseudo)
    {
        try {
            $query = $this->connection->prepare("UPDATE user SET is_verified = :ver, modified_at = CURRENT_TIMESTAMP WHERE pseudo = :pse");
            parent::setIsVerified(1);
            $query->bindValue(':ver', parent::getIsVerified());
            $query->bindValue(':pse', $pseudo);
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Verification du compte échouée : " . $e->getMessage());
        }
    }

    public function editConfigKey(string $pseudo)
    {
        try {
            $query = $this->connection->prepare("UPDATE user SET confir_key = :con, modified_at = CURRENT_TIMESTAMP WHERE pseudo = :pse");
            $query->bindValue(':con', parent::getConfirKey());
            $query->bindValue(':pse', $pseudo);
            $query->execute();
            $query->closeCursor();
        } catch (\PDOException $e) {
            die("Impossible de changer le clé de confirmation : " . $e->getMessage());
        }
    }

    public function find($search)
    {
        try {
            $query = $this->connection->prepare("SELECT * FROM user
                WHERE pseudo = :sea OR email = :sea");
            $query->bindValue('sea', clean($search));
            $query->execute();
            $query->setFetchMode(\PDO::FETCH_CLASS, User::class);
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible d'éxecuter la requête" . $e->getMessage());
        }
        return $this->items;
    }

    public function findAll()
    {
        try {
            $query = $this->connection->query("SELECT * FROM user");
            $this->items = $query->fetchAll(\PDO::FETCH_CLASS, User::class);
        } catch (\PDOException $e) {
            die("Impossible de récupérer les information : " . $e->getMessage());
        }

        return $this->items;
    }

    public function getCountLike($id) {
        try {
            $query = $this->connection->prepare("SELECT COUNT(user) as user_count FROM votes 
                WHERE user= :id AND vote = 1");
            $query->bindValue(":id", clean($id));
            $query->execute();
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible de récupérer le nombre total de likes : " . $e->getMessage());
        }

        return $this->items;
    }

    public function getCountDislike($id) {
        try {
            $query = $this->connection->prepare("SELECT COUNT(user) as user_count FROM votes 
                WHERE user= :id AND vote = -1");
            $query->bindValue(":id", clean($id));
            $query->execute();
            $this->items = $query->fetch();
        } catch (\PDOException $e) {
            die("Impossible de récupérer le nombre total de likes : " . $e->getMessage());
        }

        return $this->items;
    }
}