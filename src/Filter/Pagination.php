<?php

namespace App\Filter;

class Pagination
{
    protected string $pagesUrl;

    protected int $currentPage;

    protected int $itemsPerPage;

    protected ?int $totalItems;

    protected ?int $pageCount;

    /**
     * @param string $pagesUrl
     * @param int $currentPage
     * @param int $itemsPerPage
     */
    public function __construct(string $pagesUrl, int $currentPage, int $itemsPerPage)
    {
        $this->pagesUrl = $pagesUrl;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
    }

    function addGetParamToUrl($url, $varName, $value)
    {
        // is there already an ?
        if (strpos($url, "?")) {
            $url .= "&" . $varName . "=" . $value;
        } else {
            $url .= "?" . $varName . "=" . $value;
        }

        return $url;
    }

    public function getItems()
    {
        $maxItems = 10;
        $items = [];

        $count = 0;



        return $items;
    }

    public function getNext(): string
    {
        if ($this->currentPage < $this->pageCount) {
            return $this->addGetParamToUrl($this->pagesUrl, "page", ($this->currentPage + 1));
        }

        return "";
    }

    public function getPrevious(): string
    {
        if ($this->currentPage > 1) {
            return $this->addGetParamToUrl($this->pagesUrl, "page", ($this->currentPage - 1));
        }

        return "";
    }

    /**
     * @return string
     */
    public function getPagesUrl(): string
    {
        return $this->pagesUrl;
    }

    /**
     * @param string $pagesUrl
     */
    public function setPagesUrl(string $pagesUrl): void
    {
        $this->pagesUrl = $pagesUrl;
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
    public function getPageCount(): ?int
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