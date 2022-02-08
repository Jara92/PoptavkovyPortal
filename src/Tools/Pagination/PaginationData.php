<?php

namespace App\Tools\Pagination;

class PaginationData
{
    protected string $url;

    protected string $paramName;

    protected int $currentPage;

    protected int $itemsPerPage;

    protected ?int $totalItems;

    protected ?int $displayedItems;

    protected ?int $pageCount;

    /**
     * @param string $pagesUrl
     * @param int $currentPage
     * @param int $itemsPerPage
     */
    public function __construct(string $pagesUrl, int $currentPage, int $itemsPerPage, string $paramName)
    {
        $this->url = $pagesUrl;
        $this->paramName = $paramName;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getParamName(): string
    {
        return $this->paramName;
    }

    /**
     * @param string $paramName
     * @return PaginationData
     */
    public function setParamName(string $paramName): self
    {
        $this->paramName = $paramName;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     * @return PaginationData
     */
    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * @param int $itemsPerPage
     * @return PaginationData
     */
    public function setItemsPerPage(int $itemsPerPage): self
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    /**
     * @return ?int
     */
    public function getTotalItems(): ?int
    {
        return $this->totalItems;
    }

    /**
     * @param int $totalItems
     * @return PaginationData
     */
    public function setTotalItems(int $totalItems): self
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDisplayedItems(): ?int
    {
        return $this->displayedItems;
    }

    /**
     * @param int|null $displayedItems
     * @return PaginationData
     */
    public function setDisplayedItems(?int $displayedItems): PaginationData
    {
        $this->displayedItems = $displayedItems;
        return $this;
    }

    /**
     * @return ?int
     */
    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    /**
     * @param int $pageCount
     * @return PaginationData
     */
    public function setPageCount(int $pageCount): self
    {
        $this->pageCount = $pageCount;

        return $this;
    }


}