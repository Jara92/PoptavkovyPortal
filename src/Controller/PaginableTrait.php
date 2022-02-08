<?php

namespace App\Controller;

use App\Factory\Tools\PaginationFactory;
use App\Factory\Tools\PaginationLinkFactory;
use App\Tools\Pagination\PaginationComponent;
use Symfony\Component\HttpFoundation\Request;

trait PaginableTrait
{
    /** @required */
    public PaginationFactory $paginatorFactory;

    /** @required */
    public PaginationLinkFactory $paginationLinkFactory;

    private function getPageNumberKey(): string
    {
        return "page";
    }

    /**
     * Creates and returns pagination component.
     * @param Request $request
     * @param int $itemsPerPage
     * @return PaginationComponent
     */
    protected function getPaginationComponent(Request $request, int $itemsPerPage = 10): PaginationComponent
    {
        $page = $request->get($this->getPageNumberKey(), 1);
        $urlParam = $this->getPageNumberKey() . "=" . $page;

        $pagesUrl = str_replace(["?" . $urlParam, "&" . $urlParam], "", $request->getUri());

        $paginationData = $this->paginatorFactory->createPaginatorDefault($pagesUrl, $page, $itemsPerPage, $this->getPageNumberKey());

        return new PaginationComponent($this->paginationLinkFactory, $paginationData);
    }
}