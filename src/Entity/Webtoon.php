<?php

namespace Riotoon\Entity;

use Riotoon\Service\BuilderError;

class Webtoon
{
    private $STATUS_VALID = ['ONGOING', 'FINISHED', 'SUSPENDED'];
    private ?int $id = null;
    private ?string $title = null;
    private ?string $author = null;
    private ?string $synopsis = null;
    private ?string $cover = null;
    private ?string $status = null;
    private ?int $likes = null;
    private ?int $dislikes = null;
    private ?string $created_at = null;
    private $c_ids;
    private $categories;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle($title): self
    {
        $this->title = clean(ucwords($title));
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }
    public function setAuthor(string $author): self
    {
        $this->author = clean(ucwords($author));
        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }
    public function setSynopsis(string $synopsis): self
    {
        $synop = clean($synopsis);
        if (strlen($synop) < 10)
            BuilderError::setErrors('synopsis', 'Le synopsis doit contenir au moins 10 caractères');
        $this->synopsis = nl2br($synop);
        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }
    public function setCover(string $cover): self
    {
        if ($cover == '')
            BuildErrors::setErrors('file', 'Impossible de charger une image vide');
        $this->cover = $cover;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
    public function setStatus(string $status): self
    {
        if (!in_array($status, $this->STATUS_VALID))
            BuilderError::setErrors('status', 'Ce statut n\'est pas valide');
        $this->status = clean($status);
        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }
    public function setLikes(int $likes): self
    {
        $this->likes = $likes;
        return $this;
    }

    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }
    public function setDislikes(int $dislikes): self
    {
        $this->dislikes = $dislikes;
        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * Get the value of categories
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Get the value of c_ids
     */
    public function getIDCategories() {
        return $this->c_ids;
    }

    public function statusValid() {
        return [
            'ONGOING' => 'En cours' , 
            'FINISHED' => 'Terminé' ,
            'SUSPENDED' => 'Suspendu'
        ];
    }
}
