<?php

namespace App\Factory\Tools;

use App\Tools\Pagination\PaginationLink;

class PaginationLinkFactory
{
    public function createBlankPaginationItem():PaginationLink
    {
        return new PaginationLink(0, "", false);
    }

    public function createPaginationLink(int $id, string $url, bool $isActive = false):PaginationLink{
        return new PaginationLink($id, $url, $isActive);
    }

    public function createPaginationItem(string $url):PaginationLink{
        return new PaginationLink(0, $url, false);
    }
}