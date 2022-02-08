<?php

namespace App\Repository;

use App\Tools\Pagination\PaginationData;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

trait PaginatedRepositoryTrait
{
    /**
     * Calculated and set displayed items count.
     * @param PaginationData $paginationData
     * @return PaginationData
     */
    private function setDisplayedItems(PaginationData $paginationData): PaginationData
    {
        // Get total items and pages count
        $total = $paginationData->getTotalItems();
        $pagesCount = $paginationData->getPageCount();

        $displayedItems = 0;

        // If there are no items then no items are displayed.
        if($total == 0){
            $displayedItems = 0;
        }
        // The last page
        else if($paginationData->getCurrentPage() == $pagesCount){
            // Calculate the number of all items on all other pages.
            $itemsBefore = (($pagesCount - 1) * $paginationData->getItemsPerPage());

            // Calculate the number of items on the last page.
            $displayedItems = $total - $itemsBefore;
        }
        // Other pages contains exactly $paginationData->getItemsPerPage() items
        else if($paginationData->getCurrentPage() >= 1 && $paginationData->getCurrentPage() < $pagesCount){
            $displayedItems = $paginationData->getItemsPerPage();
        }

        $paginationData->setDisplayedItems($displayedItems);

        return $paginationData;
    }

    /**
     * Check/fix current page value.
     * @param PaginationData $paginationData
     * @return PaginationData
     */
    private function validateCurrentPage(PaginationData $paginationData) : PaginationData{
        // Given page is greater than pages count.
        if($paginationData->getCurrentPage() > $paginationData->getPageCount()){
            $paginationData->setCurrentPage($paginationData->getPageCount());
        }
        // Given page is smaller than 1.
        else if($paginationData->getCurrentPage() < 1){
            $paginationData->setCurrentPage(1);
        }

        return $paginationData;
    }

    /**
     * Setup pagination data by given query.
     * @param Query $query
     * @param PaginationData $paginationData
     * @return PaginationData
     * @throws \Exception
     */
    protected function paginate(Query $query, PaginationData $paginationData): PaginationData
    {
        // Create Symfony Paginator instance
        $paginator = new Paginator($query);

        $total = $paginator->count(); // Total items
        $pagesCount = ceil($total / $paginationData->getItemsPerPage()); // Total pages count

        // Set pagination total items and pages count.
        $paginationData->setTotalItems($total)->setPageCount($pagesCount);
        $this->validateCurrentPage($paginationData);
        $this->setDisplayedItems($paginationData);

        // Set query first item and LIMIT
        $paginator->getQuery()
            ->setFirstResult($paginationData->getItemsPerPage() * ($paginationData->getCurrentPage() - 1)) // set the offset
            ->setMaxResults($paginationData->getItemsPerPage()); // set the limit

        $paginator->getIterator();

        return $paginationData;
    }
}