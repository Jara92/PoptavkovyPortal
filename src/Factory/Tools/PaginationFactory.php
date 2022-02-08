<?php

namespace App\Factory\Tools;

use App\Tools\Pagination\PaginationData;

class PaginationFactory
{
    public function createPaginatorDefault(string $pagesUrl, int $page, int $itemsPerPage, string $paramName = "page"):PaginationData
    {
        return new PaginationData($pagesUrl, $page, $itemsPerPage, $paramName);
    }
}