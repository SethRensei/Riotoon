<?php

namespace Riotoon\Entity;

class Genre
{
    private int $id;
    private string $label;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}