<?php

namespace Riotoon\Entity;

use Riotoon\Service\BuilderError;

class Chapter
{
    private int $id;
    private string $ch_num;
    private string $ch_path;
    private $created_at;
    private int $webtoon;

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of ch_num
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->ch_num;
    }

    /**
     * Set the value of ch_num
     *
     * @param string $ch_num
     *
     * @return self
     */
    public function setNumber(string $ch_num): self
    {
        $this->ch_num = $ch_num;
        return $this;
    }

    /**
     * Get the value of ch_path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->ch_path;
    }

    /**
     * Set the value of ch_path
     *
     * @param string $ch_path
     *
     * @return self
     */
    public function setPath(string $ch_path): self
    {
        $this->ch_path = $ch_path;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Get the value of webtoon
     *
     * @return int
     */
    public function getWebtoon(): int
    {
        return $this->webtoon;
    }

    /**
     * Set the value of webtoon
     *
     * @param int $webtoon
     *
     * @return self
     */
    public function setWebtoon(int $webtoon): self
    {
        $this->webtoon = $webtoon;

        return $this;
    }
}
