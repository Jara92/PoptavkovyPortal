<?php

namespace App\Controller\Trait;

use App\Factory\Tools\PaginationFactory;
use App\Factory\Tools\PaginationLinkFactory;
use App\Tools\Pagination\PaginationComponent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Service\Attribute\Required;

trait PaginableTrait
{
    /**
     * @internal
     */
    #[Required]
    public PaginationFactory $paginatorFactory;

    /**
     * @internal
     */
    #[Required]
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