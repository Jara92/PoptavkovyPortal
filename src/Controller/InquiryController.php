<?php

namespace App\Controller;

use App\Entity\Inquiry\Inquiry;
use App\Factory\Inquiry\InquiryFactory;
use App\Form\InquiryForm;
use App\Services\InquiryService;
use Symfony\Contracts\Translation\TranslatorInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class InquiryController extends AbstractController
{
    protected $inquiryService;
    protected $inquiryFactory;
    protected $translator;

    public function __construct(InquiryService $inquiryService, TranslatorInterface $translator, InquiryFactory $inquiryFactory)
    {
        $this->inquiryService = $inquiryService;
        $this->translator = $translator;
        $this->inquiryFactory = $inquiryFactory;
    }

    /**
     * Show inquiries list.
     * @return Response
     */
    public function index(): Response
    {
        $inquiries = $this->inquiryService->readAll();

        return $this->render("inquiry/index.html.twig", ["inquiries" => $inquiries]);
    }

    /**
     * Show inquiry detail page.
     * @param string $alias
     * @return Response
     */
    public function detail(string $alias): Response
    {
        return $this->render("inquiry/detail.html.twig");
    }

    /**
     * Show and handle a new inquiry form.
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $inquiry = $this->inquiryFactory->createBlank();

        $form = $this->createForm(InquiryForm::class, $inquiry);

        // Same as $this->render('...', ['form' => $form->createView()])
        return $this->renderForm("inquiry/create.html.twig", ["form" => $form]);
    }

    /**
     * Edit an existing inquiry action.
     * @param Request $request
     * @param string $alias
     * @return Response
     */
    public function edit(Request $request, string $alias): Response
    {
        return $this->render("inquiry/edit.html.twig");
    }


}