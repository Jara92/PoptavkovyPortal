<?php

namespace App\Controller;

use App\Business\Operation\InquiryOperation;
use App\Controller\Trait\PaginableTrait;
use App\Enum\FlashMessageType;
use App\Form\Inquiry\InquiryFilterForm;
use App\Form\Inquiry\InquiryForm;
use App\Business\Service\InquiryService;
use App\Form\Inquiry\OfferForm;
use App\Tools\Filter\InquiryFilter;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class InquiryController extends AController
{
    use PaginableTrait;

    public function __construct(
        private InquiryService      $inquiryService,
        private TranslatorInterface $translator,
        private InquiryOperation    $inquiryOperation,
        private Breadcrumbs         $breadcrumbs,
        private RouterInterface     $router,
    )
    {
        $this->breadcrumbs->addItem("mainnav.home", $this->router->generate("home"));
        $this->breadcrumbs->addItem("inquiries.inquiries", $this->router->generate("inquiries"));
    }

    /**
     * Show all public inquiries list.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Get filter and return list
        $filter = $this->inquiryOperation->getDefaultFilter();
        return $this->list($request, $filter);
    }

    /**
     * Show my inquiries (all of them)
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_INQUIRING")
     */
    public function myInquiries(Request $request): Response
    {
        // Get user and check if the user is valid
        $user = $this->getUser();

        // Get filter and return list
        $filter = $this->inquiryOperation->getUserFilter($user);
        return $this->list($request, $filter);
    }

    /**
     * Show a list of inquiries given by the filter.
     * @param Request $request
     * @param InquiryFilter $filter
     * @return Response
     */
    private function list(Request $request, InquiryFilter $filter): Response
    {
        // Get pagination
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
    public function detail(string $alias, Request $request): Response
    {
        $inquiry = $this->inquiryService->readByAlias($alias);

        if (!$inquiry) {
            throw new NotFoundHttpException("Inquiry not found.");
        }

        $this->denyAccessUnlessGranted("view", $inquiry);
        $this->breadcrumbs->addItem($inquiry->getTitle(), translate: false);

        $form = null;

        // TODO move this stuff to OfferController
        if ($this->isGranted("react", $inquiry)) {
            $offer = $this->inquiryOperation->createOffer($inquiry);
            $form = $this->createForm(OfferForm::class, $offer);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $sendCopy = $form->get('sendCopy')->getData();

                $this->inquiryOperation->sendOffer($offer, $sendCopy);

                $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("offers.msg_succesfully_send"));

                // Reset the form
                $offer = $this->inquiryOperation->createOffer($inquiry);
                $form = $this->createForm(OfferForm::class, $offer);
            }
        }

        return $this->renderForm("inquiry/detail.html.twig", ["inquiry" => $inquiry, "form" => $form]);
    }

    /**
     * Show and handle a new inquiry form.
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function create(Request $request): Response
    {
        $this->breadcrumbs->addItem("inquiries.new_inquiry");

        // Get blank inquiry
        $inquiry = $this->inquiryOperation->createBlankInquiry($this->getUser());

        // Check permissions
        $this->denyAccessUnlessGranted("create", $inquiry);

        // Create inquiry form
        $form = $this->createForm(InquiryForm::class, $inquiry);

        // Handle form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Load attachments.
            $attachments = $form->get('attachments')->getData();

            dump($attachments);

            // Save the inquiry.
            $this->inquiryOperation->createInquiry($inquiry, $attachments);

            $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("inquiries.created"));

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