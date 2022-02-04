<?php

namespace App\Controller;

use App\Business\Operation\InquiryOperation;
use App\Entity\Inquiry\Inquiry;
use App\Factory\Inquiry\InquiryFactory;
use App\Form\InquiryForm;
use App\Business\Service\InquiryService;
use App\Helper\FlashHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class InquiryController extends AbstractController
{
    protected InquiryService $inquiryService;
    protected InquiryFactory $inquiryFactory;
    protected TranslatorInterface $translator;
    protected InquiryOperation $inquiryOperation;

    public function __construct(InquiryOperation $inquiryOperation, InquiryService $inquiryService, TranslatorInterface $translator, InquiryFactory $inquiryFactory)
    {
        $this->inquiryOperation = $inquiryOperation;
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
        $inquiry = $this->inquiryService->readByAlias($alias);

        if (!$inquiry) {
            throw new NotFoundHttpException("Inquiry not found.");
        }

        $this->denyAccessUnlessGranted("view", $inquiry);

        return $this->render("inquiry/detail.html.twig", ["inquiry" => $inquiry]);
    }

    /**
     * Show and handle a new inquiry form.
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $inquiry = $this->inquiryFactory->createBlank();

        $this->denyAccessUnlessGranted("create", $inquiry);

        $form = $this->createForm(InquiryForm::class, $inquiry);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            //dump($data);
            dump($inquiry);

            // Save the inquiry.
            $this->inquiryOperation->createInquiry($inquiry);

            $this->addFlash(FlashHelper::SUCCESS, $this->translator->trans("inquiries.created"));

            // TODO: remove form data.

            return $this->redirectToRoute('inquiries');
        }

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