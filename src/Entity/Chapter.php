<?php

namespace Riotoon\Entity;

use Riotoon\Service\BuildErrors;

class Chapter
{
    private int $id;
    private string $ch_num;
    private string $ch_path;
    private $modified_at;
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
    public function getChNum(): string
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
    public function setChNum(string $ch_num): self
    {
        $ch_num = ucfirst(clean($ch_num));
        if (!preg_match('/^Ch-\d+$/', $ch_num))
            BuildErrors::setErrors('ch_num', 'Doit commencer par Ch- puis poursuivre uniquement avec des chiffres');
        $this->ch_num = $ch_num;

        return $this;
    }

    /**
     * Get the value of ch_path
     *
     * @return string
     */
    public function getChPath(): string
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
    public function setChPath(string $ch_path): self
    {
        $this->ch_path = $ch_path;

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