<?php

namespace Riotoon\Entity;

class Category
{
    protected int $id;
    protected ?string $label;

    /**
     * Get the value of c_id
     *
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Get the value of label
     *
     * @return ?string
     */
    public function getLabel(): ?string {
        return $this->label;
    }

}
