<?php

namespace App\Controller;

use App\Factory\PaginatorFactory;
use App\Filter\Pagination;
use http\Url;
use Symfony\Component\HttpFoundation\Request;

trait PaginableTrait
{
    /** @required */
    public PaginatorFactory $paginatorFactory;

    private function getPageNumberKey(): string
    {
        return "page";
    }

    protected function getPagination(Request $request, $itemsPerPage = 10): Pagination
    {
        $page = $request->get($this->getPageNumberKey(), 1);
        $urlParam = $this->getPageNumberKey() . "=" . $page;

        $pagesUrl = str_replace(["?".$urlParam, "&".$urlParam], "", $request->getUri());

        return $this->paginatorFactory->createPaginatorDefault($pagesUrl, $page, $itemsPerPage);
    }
}