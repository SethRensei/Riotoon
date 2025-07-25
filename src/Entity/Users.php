<?php

namespace Riotoon\Entity;

use Riotoon\Service\BuilderError;

class Users
{
    private ?int $id = null;
    private ?string $pseudo = null;
    private ?string $email = null;
    private ?string $password = null;
    private ?string $picture = null;
    private $roles = null;
    private ?int $u_token = null;
    private ?int $token_expire = null;
    private ?bool $is_verify = null;
    private ?string $created_at = null;
    private ?string $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of pseudo
     *
     * @return ?string
     */
    public function getPseudo(): ?string {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     *
     * @param ?string $pseudo
     *
     * @return self
     */
    public function setPseudo(?string $pseudo): self {
        $pseudo = strtolower(clean($pseudo));
        if (!(strlen($pseudo) >= 5 && strlen($pseudo) <= 15))
            BuilderError::setErrors('pseudo', 'Le pseudo doit contenir entre 5-15 caractères');
        if (preg_match('/[^\w]/', $pseudo))
            BuilderError::setErrors('pseudo', "Pas de caractères spéciaux et d'espace sauf '_'");
        $this->pseudo = $pseudo;
        return $this;
    }

    /**
     * Get the value of email
     *
     * @return ?string
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param ?string $email
     *
     * @return self
     */
    public function setEmail(?string $email): self {
        $email = strtolower(clean($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            BuilderError::setErrors('email', 'Email invalid');
        $this->email = $email;
        return $this;
    }    

    /**
     * Get the value of password
     *
     * @return ?string
     */
    public function getPassword(): ?string {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param ?string $password
     *
     * @return self
     */
    public function setPassword(?string $password): self {
        $password = clean($password);
        if (!(strlen($password) >= 8 && strlen($password) <= 30))
            BuilderError::setErrors('password', 'Mot de passe non conforme aux règles');
        if (!(preg_match('/[0-9]/', $password) && preg_match('/[A-Za-z]/i', $password)))
            BuilderError::setErrors('password', 'Mot de passe non conforme aux règles');
        if(!preg_match('/[-~&?!^*#£%µ¤«§<>_@=$€»+]/', $password))
            BuilderError::setErrors('password', 'Mot de passe non conforme aux règles');
        
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Get the value of picture
     *
     * @return ?string
     */
    public function getPicture(): ?string {
        return $this->picture;
    }

    /**
     * Set the value of picture
     *
     * @param ?string $picture
     *
     * @return self
     */
    public function setPicture(?string $picture): self {
        $this->picture = $picture;
        return $this;
    }

    /**
     * Get the value of roles
     *
     * @return mixed
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * Set the value of roles
     *
     * @param ?array $roles
     *
     * @return self
     */
    public function setRoles(?array $roles): self {
        $roles[] = 'ROLE_USER';
        $this->roles = json_encode(array_unique($roles));
        return $this;
    }

    public function collectionRoles() {
        return (array) json_decode($this->getRoles() ??'');
    }

    /**
     * Get the value of u_token
     *
     * @return ?int
     */
    public function getToken(): ?int {
        return $this->u_token;
    }

    /**
     * Generate the value of u_token
     *
     * @return self
     */
    public function generateToken(): self {
        $this->u_token = mt_rand(100000, 999999);
        return $this;
    }

    /**
     * Get the value of token_expire
     *
     * @return ?int
     */
    public function getTokenExpire(): ?int {
        return $this->token_expire;
    }

    /**
     * Generate the value of token_expire
     *
     * @return self
     */
    public function generateTokenExpire(): self {
        $this->token_expire = time() + 3600; // 1h
        return $this;
    }

    /**
     * Get the value of is_verify
     *
     * @return ?bool
     */
    public function getIsVerify(): ?bool {
        return $this->is_verify;
    }

    /**
     * Set the value of is_verify
     *
     * @param ?bool $is_verify
     *
     * @return self
     */
    public function setIsVerify(?bool $is_verify): self {
        $this->is_verify = $is_verify;
        return $this;
    }

    /**
     * Get the value of created_at
     *
     * @return ?string
     */
    public function getCreatedAt(): ?string {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     *
     * @return ?string
     */
    public function getUpdatedAt(): ?string {
        return $this->updated_at;
    }
}