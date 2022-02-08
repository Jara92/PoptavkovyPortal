<?php

namespace App\Filter;

class Pagination
{
    protected int $currentPage;

    protected int $itemsPerPage;

    protected ?int $totalItems;

    protected ?int $pageCount;

    /**
     * @param int $currentPage
     * @param int $itemsPerPage
     */
    public function __construct(int $currentPage, int $itemsPerPage)
    {
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
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
     * @return Pagination
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
     * @return Pagination
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
     * @return Pagination
     */
    public function setTotalItems(int $totalItems): self
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    /**
     * @return ?int
     */
    public function getPageCount():?int
    {
        return $this->pageCount;
    }

    /**
     * @param int $pageCount
     * @return Pagination
     */
    public function setPageCount(int $pageCount): self
    {
        $this->pageCount = $pageCount;

        return $this;
    }


}