<?php

namespace App\Controller;

use App\Factory\Tools\PaginationFactory;
use App\Tools\Pagination\PaginationComponent;
use Symfony\Component\HttpFoundation\Request;

trait PaginableTrait
{
    /** @required */
    public PaginationFactory $paginatorFactory;

    private function getPageNumberKey(): string
    {
        return "page";
    }

    protected function getPagination(Request $request, $itemsPerPage = 10): PaginationComponent
    {
        $page = $request->get($this->getPageNumberKey(), 1);
        $urlParam = $this->getPageNumberKey() . "=" . $page;

        $pagesUrl = str_replace(["?".$urlParam, "&".$urlParam], "", $request->getUri());

        $paginationData = $this->paginatorFactory->createPaginatorDefault($pagesUrl, $page, $itemsPerPage, $this->getPageNumberKey());

        return new PaginationComponent($paginationData);
    }
}