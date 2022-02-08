<?php

namespace App\Factory;

use App\Filter\Pagination;

class PaginatorFactory
{
    public function createPaginatorDefault(int $page, int $itemsPerPage):Pagination
    {
        return new Pagination($page, $itemsPerPage);
    }
}