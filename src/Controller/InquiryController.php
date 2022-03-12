<?php

namespace App\Controller;

use App\Business\Operation\InquiryOperation;
use App\Business\Service\Inquiry\InquirySignedRequestService;
use App\Controller\Trait\PaginableTrait;
use App\Entity\Inquiry\InquirySignedRequest;
use App\Entity\Inquiry\Rating\InquiringRating;
use App\Enum\FlashMessageType;
use App\Form\Inquiry\InquiryFilterForm;
use App\Form\Inquiry\InquiryForm;
use App\Business\Service\Inquiry\InquiryService;
use App\Form\Inquiry\OfferForm;
use App\Form\Inquiry\Rating\InquiringRatingForm;
use App\Form\Inquiry\Rating\SupplierRatingForm;
use App\Tools\Filter\InquiryFilter;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use http\Exception\InvalidArgumentException;
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
        private InquiryService              $inquiryService,
        private InquirySignedRequestService $inquirySignedRequestService,
        private TranslatorInterface         $translator,
        private InquiryOperation            $inquiryOperation,
        private Breadcrumbs                 $breadcrumbs,
        private RouterInterface             $router,
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
        $inquiry = $this->inquiryOperation->getInquiry($alias);

        if (!$inquiry) {
            throw new NotFoundHttpException("Inquiry not found.");
        }

        $this->denyAccessUnlessGranted("view", $inquiry);

        // Increment inquiry views now
        $this->inquiryService->incHits($inquiry);

        // Get similar inquiries
        $similar = $this->inquiryOperation->getSimilarInquiries($inquiry);

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

        return $this->renderForm("inquiry/detail.html.twig", ["inquiry" => $inquiry, "similarInquiries" => $similar, "form" => $form]);
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

        // Set text according to url param
        $inquiry->setTitle($request->get("text", ""));

        // Create inquiry form
        $form = $this->createForm(InquiryForm::class, $inquiry);

        // Handle form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Load attachments.
            $attachments = $form->get('attachments')->getData();

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

    /**
     * Returns InquirySignedRequest by the request signature.
     * The request is validated.
     * @param Request $request
     * @return InquirySignedRequest
     */
    private function getInquirySignedRequest(Request $request): InquirySignedRequest
    {
        $token = $request->get("signature");
        $inquirySignedRequest = $this->inquirySignedRequestService->readBySignature($token);

        // Not found
        if (!$inquirySignedRequest) {
            throw new AccessDeniedHttpException("Invalid signature or this link has already been used.");
        }

        // Expired
        if ($inquirySignedRequest->getExpireAt()->getTimestamp() < time()) {
            throw new AccessDeniedHttpException("Link expired.");
        }

        // Invalid inquiry
        if (!$inquirySignedRequest->getInquiry()) {
            throw new InvalidArgumentException("Invalid request.");
        }

        return $inquirySignedRequest;
    }

    /**
     * Inquiry expiration postpone.
     * Requires valid expiration and signature values.
     * @param Request $request
     * @return Response
     */
    public function postponeExpiration(Request $request): Response
    {
        $inquirySignedRequest = $this->getInquirySignedRequest($request);

        // Postpone inquiry expiration.
        $this->inquiryOperation->postponeExpiration($inquirySignedRequest);

        // Flash message and redirect to inquiry detail page.
        $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("inquiries.msg_inquiry_expiration_postponed"));
        return $this->redirectToRoute("inquiries/detail", ["alias" => $inquirySignedRequest->getInquiry()->getAlias()]);
    }

    /**
     * An action to mark an inquiry as finished and receive inquiring user's rating.
     * @param Request $request
     * @return Response
     */
    public function finishInquiry(Request $request): Response
    {
        // Get inquiry from the request.
        $inquirySignedRequest = $this->getInquirySignedRequest($request);
        $inquiry = $inquirySignedRequest->getInquiry();

        // Setup breadcrumbs
        $this->breadcrumbs->addItem($inquiry->getTitle(), $this->router->generate("inquiries/detail",
            ["alias" => $inquiry->getAlias()]), translate: false);
        $this->breadcrumbs->addItem("ratings.inquiring_rating");

        // Create a rating and a form
        $rating = (new InquiringRating())->setInquiry($inquiry);
        $form = $this->createForm(InquiringRatingForm::class, $rating);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Set inquiry rating.
                $inquiry->setInquiringRating($rating);

                // Finish the inquiry using the request.
                $this->inquiryOperation->finishInquiry($inquirySignedRequest);

                // Show message and redirect away.
                $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("ratings.msg_rating_sent"));
                return $this->redirectToRoute("home");
            } // This exception is thrown if there already was a rating.
            catch (UniqueConstraintViolationException $exception) {
                $this->addFlashMessage(FlashMessageType::WARNING, $this->translator->trans("ratings.msg_rating_failed_unique"));
            } catch (Exception $ex) {
                $this->addFlashMessage(FlashMessageType::ERROR, $this->translator->trans("ratings.msg_rating_failed"));
            }
        }

        return $this->renderForm("inquiry/rating/inquiring.html.twig", compact("form", "inquiry"));
    }

    /**
     * An action to receive rating from the supplier.
     * @param Request $request
     * @return Response
     */
    public function supplierRating(Request $request): Response
    {
        // Get inquiry from the request.
        $inquirySignedRequest = $this->getInquirySignedRequest($request);
        $inquiry = $inquirySignedRequest->getInquiry();

        // Setup breadcrumbs
        $this->breadcrumbs->addItem($inquiry->getTitle(), $this->router->generate("inquiries/detail",
            ["alias" => $inquiry->getAlias()]), translate: false);
        $this->breadcrumbs->addItem("ratings.supplier_rating");

        $rating = $this->inquiryOperation->createSupplierRating($inquiry);
        $form = $this->createForm(SupplierRatingForm::class, $rating);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // set the rating.
                $inquiry->setSupplierRating($rating);

                // Save rating.
                $this->inquiryOperation->saveSupplierRating($rating);

                $this->addFlashMessage(FlashMessageType::SUCCESS, $this->translator->trans("ratings.msg_rating_sent"));
                return $this->redirectToRoute("home");
            } catch (UniqueConstraintViolationException $ex) {
                $this->addFlashMessage(FlashMessageType::WARNING, $this->translator->trans("ratings.msg_rating_failed_unique"));
            } catch (Exception $ex) {
                $this->addFlashMessage(FlashMessageType::ERROR, $this->translator->trans("ratings.msg_rating_failed"));
            }
        }

        return $this->renderForm("inquiry/rating/supplier.html.twig", compact("form", "inquiry"));
    }
}