<?php

namespace App\Tools\Pagination;

class PaginationComponent
{
    protected PaginationData $paginationData;

    public function __construct(PaginationData $paginationData)
    {
        $this->paginationData = $paginationData;
    }

    public function getPaginationData(): PaginationData
    {
        return $this->paginationData;
    }

    public function getTotalItems():int{
        return $this->paginationData->getTotalItems();
    }

    public function getDisplayedItems():int
    {
        return $this->paginationData->getDisplayedItems();
    }

    protected function addGetParamToUrl($url, $varName, $value)
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

        for ($i = max(1, $this->paginationData->getCurrentPage() - $maxItems / 2); $i < $this->paginationData->getCurrentPage(); $i++) {
            $items[] = new PaginationLink(++$count, $this->addGetParamToUrl($this->paginationData->getPagesUrl(), "page", $i), false);
        }

        $items[] = new PaginationLink(++$count, $this->addGetParamToUrl($this->paginationData->getPagesUrl(), "page", $this->paginationData->getCurrentPage()), true);

        for ($i = $this->paginationData->getCurrentPage() + 1; $i <= $this->paginationData->getPageCount() && $count <= $maxItems; $i++) {
            $items[] = new PaginationLink(++$count, $this->addGetParamToUrl($this->paginationData->getPagesUrl(), "page", $i), false);
        }

        return $items;
    }

    public function getNext(): ?PaginationLink
    {
        if ($this->paginationData->getCurrentPage() < $this->paginationData->getPageCount()) {
            return new PaginationLink(0, $this->addGetParamToUrl($this->paginationData->getPagesUrl(), "page", ($this->paginationData->getCurrentPage() + 1)), false);
            //return $this->addGetParamToUrl($this->$this->pagination->$this->getPagesUrl(), "page", ($this->pagination->getCurrentPage() + 1));
        }

        return new PaginationLink(0, "", false);
    }

    public function getPrevious(): ?PaginationLink
    {
        if ($this->paginationData->getCurrentPage() > 1) {
            return new PaginationLink(0, $this->addGetParamToUrl($this->paginationData->getPagesUrl(), "page", ($this->paginationData->getCurrentPage() - 1)), false);
        }

        return new PaginationLink(0, "", false);
    }
}