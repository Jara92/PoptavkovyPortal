<?php

namespace App\Tools\Pagination;

use App\Factory\Tools\PaginationLinkFactory;

class PaginationComponent
{
    protected PaginationLinkFactory $paginationLinkFactory;

    protected PaginationData $data;

    public function __construct(PaginationLinkFactory $paginationLinkFactory, PaginationData $paginationData)
    {
        $this->paginationLinkFactory = $paginationLinkFactory;
        $this->data = $paginationData;
    }

    public function getData(): PaginationData
    {
        return $this->data;
    }

    public function getTotalItems(): int
    {
        return $this->data->getTotalItems();
    }

    public function getDisplayedItems(): int
    {
        return $this->data->getDisplayedItems();
    }

    /**
     * Ads given $param and its $value to given $url.
     * @param $url
     * @param $param
     * @param $value
     * @return string
     */
    protected function addUrlParam($url, $param, $value): string
    {
        // is there already an ?
        if (strpos($url, "?")) {
            $url .= "&" . $param . "=" . $value;
        } else {
            $url .= "?" . $param . "=" . $value;
        }

        return $url;
    }

    /**
     * Returns pagination link items.
     * @return PaginationLink[]
     */
    public function getItems(): array
    {
        $maxItems = 10;
        $currentPage = $this->data->getCurrentPage();

        $items = [];

        $count = 0;

        // Pages smaller than current page.
        for ($i = max(1, $currentPage - $maxItems / 2); $i < $currentPage; $i++) {
            $url = $this->addUrlParam($this->data->getUrl(), $this->data->getParamName(), $i);
            $items[] = $this->paginationLinkFactory->createPaginationLink(++$count, $url);
        }

        // Current page.
        $currentUrl = $this->addUrlParam($this->data->getUrl(), $this->data->getParamName(), $currentPage);
        $items[] = $this->paginationLinkFactory->createPaginationLink(++$count, $currentUrl, true);

        // Pages bigger than current page. W
        for ($i = $currentPage + 1; $i <= $this->data->getPageCount() && $count <= $maxItems; $i++) {
            $url = $this->addUrlParam($this->data->getUrl(), $this->data->getParamName(), $i);
            $items[] = $this->paginationLinkFactory->createPaginationLink(++$count, $url);
        }

        return $items;
    }

    /**
     * Returns PaginationLink to next page.
     * @return PaginationLink|null
     */
    public function getNext(): ?PaginationLink
    {
        if ($this->data->getCurrentPage() < $this->data->getPageCount()) {
            $url = $this->addUrlParam($this->data->getUrl(), $this->data->getParamName(), ($this->data->getCurrentPage() + 1));
            return $this->paginationLinkFactory->createPaginationItem($url);
        }

        return $this->paginationLinkFactory->createBlankPaginationItem();
    }

    /**
     * Returns PaginationLink to previous page.
     * @return PaginationLink|null
     */
    public function getPrevious(): ?PaginationLink
    {
        if ($this->data->getCurrentPage() > 1) {
            $url = $this->addUrlParam($this->data->getUrl(), $this->data->getParamName(), ($this->data->getCurrentPage() - 1));
            return $this->paginationLinkFactory->createPaginationItem($url);
        }

        return $this->paginationLinkFactory->createBlankPaginationItem();
    }
}