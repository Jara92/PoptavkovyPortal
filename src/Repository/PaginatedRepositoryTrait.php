<?php

namespace App\Repository;

use App\Filter\Pagination;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

trait PaginatedRepositoryTrait
{
    protected function paginate(Query $query, Pagination $pagination): Pagination
    {
        // Create Symfony Paginator instance
        $paginator = new Paginator($query);

        $total = $paginator->count(); // Total items
        $pagesCount = ceil($total / $pagination->getItemsPerPage()); // Total pages count

        // Set pagination values
        $pagination->setTotalItems($total)->setPageCount($pagesCount);

        // Set query first item and LIMIT
        $query
            ->setFirstResult($pagination->getItemsPerPage() * ($pagination->getCurrentPage() - 1)) // set the offset
            ->setMaxResults($pagination->getItemsPerPage()); // set the limit

        return $pagination;
    }
}