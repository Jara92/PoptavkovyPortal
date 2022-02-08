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

    /**
     * @return PaginationLink[]
     */
    public function getItems()
    {
        $maxItems = 10;


        $items = [];

        $count = 0;

        for ($i = max(1, $this->currentPage - $maxItems / 2); $i < $this->currentPage; $i++) {
            $items[] =  new PaginationLink(++$count, $this->addGetParamToUrl($this->pagesUrl, "page", $i), false);
        }

        $items[] = new PaginationLink(++$count, $this->addGetParamToUrl($this->pagesUrl, "page", $this->currentPage), true);

        for ($i = $this->currentPage + 1; $i <= $this->pageCount && $count <= $maxItems; $i++) {
            $items[] = new PaginationLink(++$count, $this->addGetParamToUrl($this->pagesUrl, "page", $i), false);
        }

        return $items;
    }

    public function getNext(): ?PaginationLink
    {
        if ($this->currentPage < $this->pageCount) {
            return new PaginationLink(0, $this->addGetParamToUrl($this->pagesUrl, "page", ($this->currentPage + 1)), false);
            //return $this->addGetParamToUrl($this->pagesUrl, "page", ($this->currentPage + 1));
        }

        return new PaginationLink(0, "", false);
    }

    public function getPrevious(): ?PaginationLink
    {
        if ($this->currentPage > 1) {
            return new PaginationLink(0, $this->addGetParamToUrl($this->pagesUrl, "page", ($this->currentPage - 1)), false);
        }

        return new PaginationLink(0, "", false);
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