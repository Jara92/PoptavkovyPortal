<?php

namespace App\Factory;

use App\Filter\Pagination;

class PaginatorFactory
{
    public function createPaginatorDefault(string $pagesUrl, int $page, int $itemsPerPage):Pagination
    {
        return new Pagination($pagesUrl, $page, $itemsPerPage);
    }
}