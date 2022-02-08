<?php

namespace App\Controller;

use App\Business\Operation\InquiryOperation;
use App\Factory\Inquiry\InquiryFactory;
use App\Factory\InquiryFilterFactory;
use App\Form\InquiryFilterForm;
use App\Form\InquiryForm;
use App\Business\Service\InquiryService;
use App\Helper\FlashHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InquiryController extends AController
{
    use PaginableTrait;

    protected InquiryService $inquiryService;
    protected InquiryFactory $inquiryFactory;
    protected TranslatorInterface $translator;
    protected InquiryOperation $inquiryOperation;

    /** @required */
    public InquiryFilterFactory $inquiryFilterFactory;

    public function __construct(InquiryOperation $inquiryOperation, InquiryService $inquiryService, TranslatorInterface $translator, InquiryFactory $inquiryFactory)
    {
        $this->inquiryOperation = $inquiryOperation;
        $this->inquiryService = $inquiryService;
        $this->translator = $translator;
        $this->inquiryFactory = $inquiryFactory;
    }

    /**
     * Show inquiries list.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Get filter and paginator
        $filter = $this->inquiryFilterFactory->createBlankInquiryFilter();
        $pagination = $this->getPaginationComponent($request, $this->getParameter("app.items_per_page"));

        // Create filter form.
        $form = $this->createForm(InquiryFilterForm::class, $filter);

        $form->handleRequest($request);

        // Get all inquiries using the filter.
        $inquiries = $this->inquiryService->readAllFiltered($filter, $pagination->getData());

        return $this->renderForm("inquiry/index.html.twig",
            ["form" => $form, "pagination" => $pagination, "inquiries" => $inquiries]);
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
     * @throws \Exception
     */
    public function create(Request $request): Response
    {
        // Create blank inquiry
        $inquiry = $this->inquiryFactory->createBlank();

        // Check permissions
        $this->denyAccessUnlessGranted("create", $inquiry);

        // Fill inquiry contact data if possible
        $this->inquiryOperation->fillUserData($inquiry, $this->getUser());

        // Create inquiry form
        $form = $this->createForm(InquiryForm::class, $inquiry);

        // Handle form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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