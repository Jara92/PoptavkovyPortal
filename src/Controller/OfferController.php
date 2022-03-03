<?php

namespace App\Controller;

use App\Business\Operation\InquiryOperation;
use App\Business\Service\OfferService;
use App\Controller\Trait\PaginableTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class OfferController extends AController
{
    use PaginableTrait;

    public function __construct(
        private TranslatorInterface $translator,
        private InquiryOperation    $inquiryOperation,
        private OfferService        $offerService,
        private Breadcrumbs         $breadcrumbs,
        private RouterInterface     $router,
    )
    {
    }

    /**
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_SUPPLIER")
     */
    public function listAction(Request $request): Response
    {
        // Get pagination
        $pagination = $this->getPaginationComponent($request, $this->getParameter("app.items_per_page"));

        // Get all inquiries using the filter.
        $offers = $this->offerService->readByAuthor($this->getUser(), $pagination->getData());

        return $this->renderForm("offer/index.html.twig",
            ["pagination" => $pagination, "offers" => $offers]);
    }
}