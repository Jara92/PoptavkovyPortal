<?php

namespace App\Controller;

use App\Factory\PaginatorFactory;
use App\Filter\Pagination;
use Symfony\Component\HttpFoundation\Request;

trait PaginableTrait
{
    /** @required */
    public PaginatorFactory $paginatorFactory;

    private function getPageNumberKey(): string
    {
        return "page";
    }

    protected function getPaginator(Request $request, $itemsPerPage = 10): Pagination
    {
        $page = $request->get($this->getPageNumberKey(), 1);

        return $this->paginatorFactory->createPaginatorDefault($page, $itemsPerPage);
    }
}