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
        return $this->render("inquiries/index.html.twig");
    }

    /**
     * Show inquiry detail page.
     * @param string $alias
     * @return Response
     */
    public function detail(string $alias)
    {
        return $this->render("inquiries/detail.html.twig");
    }

    /**
     * Show and handle a new inquiry form.
     * @return Response
     */
    public function create(Request $request)
    {
        return $this->render("inquiries/create.html.twig");
    }

    /**
     * Edit an existing inquiry action.
     * @param Request $request
     * @param string $alias
     * @return Response
     */
    public function edit(Request $request, string $alias)
    {
        return $this->render("inquiries/edit.html.twig");
    }


}