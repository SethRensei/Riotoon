<?php

namespace Riotoon\Entity;

class Genre
{
    private int $id;
    private string $label;
    private ?int $webtoon_count;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }


    /**
     * Set the value of label
     *
     * @param string $label
     *
     * @return self
     */
    public function setLabel(string $label): self
    {
        $this->label = ucwords($label);

        return $this;
    }

    /**
     * Get the value of webtoon_count
     *
     * @return int|null
     */
    public function getWebtoonCount(): ?int
    {
        return $this->webtoon_count;
    }
}