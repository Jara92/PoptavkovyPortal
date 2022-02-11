<?php

namespace App\Tools\Pagination;

class PaginationLink
{
    protected int $id;

    protected ?string $url;

    protected bool $isActive;

    /**
     * @param $id
     * @param $url
     * @param $isActive
     */
    public function __construct($id, $url, $isActive)
    {
        $this->id = $id;
        $this->url = $url;
        $this->isActive = $isActive;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return PaginationLink
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     * @return PaginationLink
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return PaginationLink
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }



    public function getIsValid(): bool
    {
        return $this->url !== "";
    }
}