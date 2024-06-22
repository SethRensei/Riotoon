<?php

namespace Riotoon\Entity;

use Riotoon\Service\BuildErrors;

class User
{
    private ?int $id;
    private ?string $pseudo;
    private ?string $fullname;
    private ?string $email;
    private $roles = ['ROLE_USER'];
    private ?string $password;
    private ?int $confir_key;
    private ?int $is_verified = 0;
    private $modified_at;

    /**
     * Get the value of id
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of pseudo
     *
     * @return string|null
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     *
     * @param string $pseudo
     *
     * @return self
     */
    public function setPseudo(string $pseudo): self
    {
        $pseudo = strtolower(clean($pseudo));
        if (!(strlen($pseudo) >= 5 && strlen($pseudo) <= 15))
            BuildErrors::setErrors('pseudo', 'Le pseudo doit contenir entre 5-15 caractères');
        if (preg_match('/[^\w]/', $pseudo))
            BuildErrors::setErrors('pseudo', "Pas de caractères spéciaux et d'espace sauf '_'");

        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get the value of fullname
     *
     * @return string|null
     */
    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    /**
     * Set the value of fullname
     *
     * @param string $fullname
     *
     * @return self
     */
    public function setFullname(string $fullname): self
    {
        $fullname = ucwords(clean($fullname));
        if (!(preg_match('/^[a-zA-Z\s]+$/', $fullname)))
            BuildErrors::setErrors('fullname', 'Ne contient que des lettres et espaces');
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get the value of email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $email = strtolower(clean($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            BuildErrors::setErrors('email', 'Email invalid');
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of roles
     *
     * @return mixed
     */
    public function getRoles()
    {
        return json_encode($this->roles);
    }

    /**
     * Set the value of roles
     *
     * @param array $roles
     *
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $roles[] = 'ROLE_USER';
        $this->roles = json_encode(array_unique($roles));
        return $this;
    }

    /**
     * Get the value of password
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $password = clean($password);
        if (!(strlen($password) >= 8 && strlen($password) <= 30))
            BuildErrors::setErrors('password', 'Mot de passe non conforme aux règles');
        if (!(preg_match('/[0-9]/', $password) && preg_match('/[A-Za-z]/i', $password)))
            BuildErrors::setErrors('password', 'Mot de passe non conforme aux règles');
        if(!preg_match('/[-~&?!^*#£%µ¤«§<>_@=$€»+]/', $password))
            BuildErrors::setErrors('password', 'Mot de passe non conforme aux règles');
        
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    /**
     * Get the value of is_verified
     *
     * @return int|null
     */
    public function getIsVerified(): ?int
    {
        return $this->is_verified;
    }

    /**
     * Set the value of is_verified
     *
     * @param int $is_verified
     *
     * @return self
     */
    public function setIsVerified(int $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    /**
     * Get the value of modified_at
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * Get the value of confir_key
     *
     * @return int
     */
    public function getConfirKey(): int
    {
        return $this->confir_key;
    }

    /**
     * Update the value of confir_key
     *
     *
     * @return self
     */
    public function updateConfirKey(): self
    {
        $this->confir_key = mt_rand(100000, 999999);

        return $this;
    }

    /**
     * Returns the roles of the user as decoded from JSON.
     * @return array The roles of the user
     */
    public function getCollectionsRoles()
    {
        return (array)json_decode(json_decode($this->roles));
    }
}