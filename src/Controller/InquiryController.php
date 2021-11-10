<?php

namespace App\Controller;

use App\Services\InquiryService;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class InquiryController extends AbstractController
{
    protected $inquiryService;

    public function __construct(InquiryService $inquiryService)
    {
        $this->inquiryService = $inquiryService;
    }

    /**
     * Show inquiries list.
     * @return Response
     */
    public function index()
    {
        return $this->render("layouts/app.html.twig");
    }

    /**
     * Show inquiry detail page.
     * @param Request $request
     * @param string $alias
     * @return Response
     */
    public function detail(Request $request, string $alias)
    {

    }
}