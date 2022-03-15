<?php

namespace App\Business\Operation;

use App\Business\Service\Inquiry\DeadlineService;
use App\Business\Service\Inquiry\InquiryAttachmentService;
use App\Business\Service\Inquiry\InquiryService;
use App\Business\Service\Inquiry\InquirySignedRequestService;
use App\Business\Service\Inquiry\InquiryValueService;
use App\Business\Service\Inquiry\OfferService;
use App\Business\Service\Inquiry\Rating\SupplierRatingService;
use App\Business\Service\Inquiry\SmartTagService;
use App\Entity\Company;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquirySignedRequest;
use App\Entity\Inquiry\Offer;
use App\Entity\Inquiry\Rating\SupplierRating;
use App\Enum\Entity\InquiryState;
use App\Enum\Entity\InquiryType;
use App\Entity\Person;
use App\Entity\User;
use App\Enum\Entity\UserType;
use App\Exception\AlreadyMadeOfferException;
use App\Factory\Inquiry\CompanyContactFactory;
use App\Factory\Inquiry\InquiryAttachmentFactory;
use App\Factory\Inquiry\InquiryFactory;
use App\Factory\Inquiry\OfferFactory;
use App\Factory\Inquiry\PersonalContactFactory;
use App\Factory\InquiryFilterFactory;
use App\Helper\InquiryStateHelper;
use App\Helper\UrlHelper;
use App\Security\UserSecurity;
use App\Tools\Filter\InquiryFilter;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use DateTime;
use LogicException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class InquiryOperation
{
    public function __construct(
        private InquiryService              $inquiryService,
        private InquiryAttachmentService    $attachmentService,
        private InquiryValueService         $inquiryValueService,
        private SupplierRatingService       $supplierRatingService,
        private DeadlineService             $deadlineService,
        private SubscriptionOperation       $subscriptionOperation,
        private InquiryFactory              $inquiryFactory,
        private InquiryAttachmentFactory    $attachmentFactory,
        private InquiryFilterFactory        $filterFactory,
        private OfferService                $offerService,
        private OfferFactory                $offerFactory,
        private SmartTagService             $smartTagService,
        private InquirySignedRequestService $inquirySignedRequestService,
        private PersonalContactFactory      $personalContactFactory,
        private CompanyContactFactory       $companyContactFactory,
        private UserSecurity                $security,
        private SluggerInterface            $slugger,
        private ContainerBagInterface       $params,
        private TranslatorInterface         $translator,
        private MailerInterface             $mailer,
        private RouterInterface             $router,
        private UrlSignerInterface          $urlSigner,
    )
    {
    }

    /**
     * Returns inquiry if found with corresponding smart tags attached.
     * @param string $alias
     * @return Inquiry|null
     */
    public function getInquiry(string $alias): ?Inquiry
    {
        $inquiry = $this->inquiryService->readByAlias($alias);
        if ($inquiry) {
            $this->attachSmartTags($inquiry);
        }

        return $inquiry;
    }

    /**
     * Attaches smart tags which corresponds to the inquiry.
     * @param Inquiry $inquiry
     */
    public function attachSmartTags(Inquiry $inquiry): void
    {
        foreach ($this->smartTagService->readAll() as $smartTag) {
            if ($smartTag->correspondsTo($inquiry)) {
                $inquiry->addSmartTag($smartTag);
            }
        }
    }

    /**
     * Get default inquiry type for the user.
     * @param User|null $user
     * @return InquiryType|null
     */
    public function getNewInquiryDefaultType(?User $user): ?InquiryType
    {
        // According to userType set default Inquiry type.
        if ($user) {
            if ($user->isType(UserType::PERSON)) {
                return InquiryType::PERSONAL;
            } else if ($user->isType(UserType::COMPANY)) {
                return InquiryType::COMPANY;
            }
        }

        return InquiryType::PERSONAL;
    }

    /**
     * Returns an inquiry filter objects with default options.
     * @return InquiryFilter
     */
    public function getDefaultFilter(): InquiryFilter
    {
        // Default inquiry states visible for all users.
        return $this->filterFactory->createInquiryFilter(InquiryStateHelper::getPublicStates());
    }

    /**
     * Returns an inquiry filter object with current user as an author.
     * @param User $user
     * @return InquiryFilter
     */
    public function getUserFilter(User $user): InquiryFilter
    {
        return $this->filterFactory->createBlankInquiryFilter()->setAuthor($user);
    }

    /**
     * Create a new inquiry.
     * @param Inquiry $inquiry
     * @param UploadedFile[] $attachments
     * @return bool
     */
    public function createInquiry(Inquiry $inquiry, array $attachments = []): bool
    {
        // Remove useless contact object.
        if ($inquiry->isType(InquiryType::PERSONAL)) {
            $inquiry->setCompanyContact(null);
        } else if ($inquiry->isType(InquiryType::COMPANY)) {
            $inquiry->setPersonalContact(null);
        }

        // Set state
        $inquiry->setState(InquiryState::STATE_NEW);

        // Set inquiry author.
        $inquiry->setAuthor($this->security->getUser());

        // Guess value and deadline by given text
        $this->guessValue($inquiry);
        $this->guessDeadline($inquiry);

        $this->inquiryService->create($inquiry);

        // Generate inquiry alias and update entity.
        $inquiry->setAlias(UrlHelper::createIdAlias($inquiry->getId(), $inquiry->getTitle()));
        $this->inquiryService->update($inquiry);

        // If there are any attachments, save them
        if (!empty($attachments)) {
            $this->saveAttachments($inquiry, $attachments);
        }

        return true;
    }

    /**
     * Figures out and updates inquiry value.
     * @param Inquiry $inquiry
     */
    private function guessValue(Inquiry $inquiry): void
    {
        // Nothing to be done if there is no input.
        if (!$inquiry->getValueText()) {
            return;
        }

        // Try to guess value
        $guessedValue = $this->inquiryValueService->figureOut($inquiry->getValueText());
        if ($guessedValue) {
            $inquiry->setValue($guessedValue);
            return;
        }

        // Try to convert the text to int
        $value = intval($inquiry->getValueText());
        if ($value > 0) {
            $inquiry->setValueNumber($value);
        }
    }

    /**
     * Figures out and updates deadline.
     * @param Inquiry $inquiry
     */
    private function guessDeadline(Inquiry $inquiry): void
    {
        // Nothing to be done if there is no input.
        if (!$inquiry->getDeadlineText()) {
            return;
        }

        // Try to guess value
        $guessedDeadline = $this->deadlineService->figureOut($inquiry->getDeadlineText());
        if ($guessedDeadline) {
            $inquiry->setDeadline($guessedDeadline);
        }
    }

    /**
     * @param Inquiry $inquiry
     * @param UploadedFile[] $attachments
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function saveAttachments(Inquiry $inquiry, array $attachments)
    {
        $attachmentEntities = [];

        // Directory for the entitie's attachments.
        $directory = $this->params->get("app.inquiries.attachments_directory");
        $directory .= "/" . $inquiry->getId();

        foreach ($attachments as $attachment) {
            $fileName = pathinfo($attachment->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $this->slugger->slug($fileName);
            $newFilename = $safeFilename . '.' . $attachment->guessExtension();
            $type = $attachment->guessExtension();
            $hash = hash_file("sha256", $attachment->getRealPath());
            $description = "";
            $size = $attachment->getSize();
            $path = $directory . "/" . $newFilename;

            // Create an entity using the params.
            $entity = $this->attachmentFactory->createAttachment($inquiry, $newFilename, $hash, $description, $size, $path, $type);

            // Move the file to the directory where brochures are stored
            try {
                $attachment->move(
                    $directory,
                    $newFilename
                );

                // Add entity into the array to be saved.
                $attachmentEntities[] = $entity;

            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
        }

        // Save new entities
        $this->attachmentService->createAll($attachmentEntities);
    }

    /**
     * Creates a blank inquiry and fills default inquiry data for given user.
     * @param User|null $user
     * @return Inquiry
     */
    public function createBlankInquiry(?User $user): Inquiry
    {
        // Create blank inquiry
        $inquiry = $this->inquiryFactory->createBlank();

        // Set type inquiry type.
        $inquiry->setType($this->getNewInquiryDefaultType($user));

        // No autofill for unauthenticated user.
        if (is_null($user)) {
            return $inquiry;
        }

        // Returns inquiry and autofilled data.
        return $this->fillContactData($inquiry, $user);
    }

    private function fillContactData(Inquiry $inquiry, User $user): Inquiry
    {
        // Common user data
        $inquiry->setContactEmail($user->getEmail());
        $inquiry->setContactPhone($user->getPhone());

        // Personal user
        if ($user->isType(UserType::PERSON)) {
            return $this->fillPersonContactData($inquiry, $user->getPerson());
        } // Company user
        else if ($user->isType(UserType::COMPANY)) {
            return $this->fillCompanyContactData($inquiry, $user->getCompany());
        }

        throw new LogicException("Unknown user type: " . $user->getType()->value);
    }

    private function fillPersonContactData(Inquiry $inquiry, ?Person $person): Inquiry
    {
        // Check if person is valid.
        if (is_null($person)) {
            throw new LogicException("User.person must not be null for this type of user!");
        }

        // Create new personcal contact instance.
        $personalContact = $this->personalContactFactory->createPersonalContact($person->getName(), $person->getSurname());
        $inquiry->setPersonalContact($personalContact);

        return $inquiry;
    }

    private function fillCompanyContactData(Inquiry $inquiry, ?Company $company): Inquiry
    {
        // Check if person is valid.
        if (is_null($company)) {
            throw new LogicException("User.person must not be null for this type of user!");
        }

        // Create new company contact instance.
        $companyContact = $this->companyContactFactory->createCompanyContact($company->getName(), $company->getIdentificationNumber());
        $inquiry->setCompanyContact($companyContact);

        return $inquiry;
    }

    /**
     * Creates an offer for the given supplier by signed user.
     * Returns existing inquiry if there already was one.
     * @param Inquiry $inquiry
     * @return Offer
     */
    public function createOffer(Inquiry $inquiry): Offer
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException("Use must be authorized to do this!");
        }

        $offer = $this->offerService->readOneByInquiryAndAuthor($inquiry, $user);
        if ($offer) {
            return $offer;
        } else {
            return $this->offerFactory->createOffer($user, $inquiry);
        }
    }

    /**
     * Saves and send the offer if there is no offer with the same inquiry and user.
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws AlreadyMadeOfferException If the user already made an offer for this inquiry.
     */
    public function sendOffer(Offer $newOffer, bool $sendCopy = false)
    {
        // Check existence of an offer with the same inquiry and user.
        $offer = $this->offerService->readOneByInquiryAndAuthor($newOffer->getInquiry(), $newOffer->getAuthor());
        if ($offer) {
            throw new AlreadyMadeOfferException("You have already made an offer");
        }

        // Save the offer
        $this->offerService->create($newOffer);

        // Notification email to the inquiry author.
        $this->sendOfferEmailToInquiring($newOffer);

        // Send copy to offer author.
        if ($sendCopy) {
            $this->sendOfferEmailToSupplier($newOffer);
        }
    }

    // TODO: REFACTOR SENDING OFFERS

    /**
     * Sends an email to the inquiring of the offer inquriry.
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function sendOfferEmailToInquiring(Offer $offer)
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->params->get("app.email"), $this->params->get("app.name")))
            ->to($offer->getInquiry()->getContactEmail())
            ->subject($this->translator->trans("offers.new_inquiry_offer") . " #" . $offer->getInquiry()->getId())
            ->htmlTemplate('email/inquiry/offer_inquiring.html.twig')
            ->context(["offer" => $offer]);

        $this->mailer->send($email);
    }

    /**
     * Sends an email to the supplier of the offer.
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function sendOfferEmailToSupplier(Offer $offer)
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->params->get("app.email"), $this->params->get("app.name")))
            ->to($offer->getAuthor()->getEmail())
            ->subject($this->translator->trans("offers.new_inquiry_offer_copy") . " #" . $offer->getInquiry()->getId())
            ->htmlTemplate('email/inquiry/offer_supplier.html.twig')
            ->context(["offer" => $offer]);

        $this->mailer->send($email);
    }

    /**
     * Return an array of InquiryTypes which can be created by the user.
     * @throws LogicException User has no relevant role.
     * @return array
     */
    public function getAvailableInquiryTypesToCreate(): array
    {
        // Unauthorized user can create personal or company inquiry.
        if (!$this->security->getUser()) {
            return [InquiryType::PERSONAL, InquiryType::COMPANY];
        }

        // A company can create only company inquiries.
        if ($this->security->getUser()->isType(UserType::COMPANY)) {
            return [InquiryType::COMPANY];
        } // A person can create only personal inquriies.
        else if ($this->security->getUser()->isType(UserType::PERSON)) {
            return [InquiryType::PERSONAL];
        }

        throw new LogicException("User has no relevant role.");
    }

    /**
     * Updates inquiry and checks whether the inquiry is newly published.
     * @param Inquiry $inquiry
     */
    public function updateInquiry(Inquiry $inquiry): void
    {
        // Is the inquiry published now?
        if ($inquiry->getState() == InquiryState::STATE_ACTIVE && !$inquiry->getPublishedAt()) {
            $now = new DateTime();
            $inquiry->setPublishedAt($now);

            $this->updateAutoRemoveData($inquiry);

            $this->subscriptionOperation->handleNewInquiry($inquiry);
        }

        // Update data
        $this->inquiryService->update($inquiry);
    }

    /**
     * Returns the given number of inquiries similar to the given inquiry.
     * @param Inquiry $inquiry
     * @param int $maxResults
     * @return array
     */
    public function getSimilarInquiries(Inquiry $inquiry, int $maxResults = 10): array
    {
        return $this->inquiryService->readSimilar($inquiry, $maxResults);
    }

    /**
     * Handles inquiries which are going to be removed.
     * This is handled by detect
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @return int[] Array in format [$removedCount, $notifiedCount]
     */
    public function handleOldInquiries(): array
    {
        // Inquiries which are going to be removed will be notified about it.
        $inquiries = $this->inquiryService->readActiveToBeNotified();
        $notifiedCnt = 0;
        foreach ($inquiries as $inquiry) {
            $this->autoRemoveNotify($inquiry);
            $notifiedCnt++;
        }

        // Inquiries which will be removed.
        $inquiries = $this->inquiryService->readActiveToBeRemoved();
        $removedCnt = 0;
        foreach ($inquiries as $inquiry) {
            $this->autoRemove($inquiry);
            $removedCnt++;
        }

        return [$removedCnt, $notifiedCnt];
    }

    /**
     * Sends an email which notifies the user about inquiry expiration.
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function autoRemoveNotify(Inquiry $inquiry): void
    {
        // Calculate links expiration datetime.
        $expirationSeconds = $this->params->get("app.inquiries.auto_remove_delay");
        $expiration = (new DateTime("now + $expirationSeconds seconds"));

        // Build finish inquiry link and sign it.
        $finishInquiryUrl = $this->router->generate("inquiries/finish-signed", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $finishInquiryUrl = $this->urlSigner->sign($finishInquiryUrl, $expiration);

        // Build postpone expiration link and sign it.
        $postponeExpirationUrl = $this->router->generate("inquiries/postpone-expiration", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $postponeExpirationUrl = $this->urlSigner->sign($postponeExpirationUrl, $expiration);

        // Build and send the email
        $email = (new TemplatedEmail())
            ->from(new Address($this->params->get("app.email"), $this->params->get("app.name")))
            ->to($inquiry->getContactEmail())
            ->subject($this->translator->trans("inquiries.inquiry_will_be_removed"))
            ->htmlTemplate('email/inquiry/expiration_notify.html.twig')
            ->context(["inquiry" => $inquiry, "finishUrl" => $finishInquiryUrl, "postponeUrl" => $postponeExpirationUrl]);

        $this->mailer->send($email);

        // Create requests
        $finishRequest = (new InquirySignedRequest())->setInquiry($inquiry)->setExpireAt($expiration)
            ->setToken($this->getTokenFromSignedUrl($finishInquiryUrl));

        $postponeRequest = (new InquirySignedRequest())->setInquiry($inquiry)->setExpireAt($expiration)
            ->setToken($this->getTokenFromSignedUrl($postponeExpirationUrl));

        // Set request user if the inquiry has an author.
        if ($inquiry->getAuthor()) {
            $finishRequest->setUser($inquiry->getAuthor());
            $postponeRequest->setUser($inquiry->getAuthor());
        }

        // Save the requests
        $this->inquirySignedRequestService->create($finishRequest);
        $this->inquirySignedRequestService->create($postponeRequest);

        // Remove next notification date
        $inquiry->setRemoveNoticeAt(null);
        $this->inquiryService->update($inquiry);
    }

    /**
     * Postpone inquiry expiration.
     * @param InquirySignedRequest $inquirySignedRequest
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function postponeExpiration(InquirySignedRequest $inquirySignedRequest): void
    {
        $this->updateAutoRemoveData($inquirySignedRequest->getInquiry());

        // Update inquiry data
        $this->inquiryService->update($inquirySignedRequest->getInquiry());

        // Remove inquiry signed request to prevent multiple times access.
        $this->inquirySignedRequestService->delete($inquirySignedRequest);
    }

    /**
     * Remove expired inquiry.
     * @param Inquiry $inquiry
     */
    private function autoRemove(Inquiry $inquiry): void
    {
        // Mark the inquiry as archived.
        $inquiry->setState(InquiryState::STATE_ARCHIVED);

        // Make removeAt field null to make sure that inquiry is not removed more times.
        $inquiry->setRemoveAt(null);
        $this->inquiryService->update($inquiry);
    }

    /**
     * Updates inquiry fields used for removing expired inquiries.
     * @param Inquiry $inquiry
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function updateAutoRemoveData(Inquiry $inquiry): void
    {
        // Set remove notification date
        $notificationSeconds = $this->params->get("app.inquiries.auto_remove_notification_delay");
        $notificationAt = new DateTime("+ $notificationSeconds seconds");
        $inquiry->setRemoveNoticeAt($notificationAt);

        // Set actual removing date
        $removeSeconds = $notificationSeconds + $this->params->get("app.inquiries.auto_remove_delay");
        $removeAt = new DateTime("+ $removeSeconds seconds");
        $inquiry->setRemoveAt($removeAt);
    }

    /**
     * Returns all suppliers which have made an offer for given inquiry.
     * @param Inquiry $inquiry
     * @return User[]
     */
    public function getInquiryOffersSuppliers(Inquiry $inquiry): array
    {
        return array_map(fn(Offer $offer) => $offer->getAuthor(), $inquiry->getOffers()->toArray());
    }

    private function getTokenFromSignedUrl(string $url): string
    {
        $signatureParamName = $this->params->get("app.parameter_signature");
        return explode("$signatureParamName=", $url)[1];
    }

    /**
     * Sends notification that inquiry rating is available for the supplier.
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    private function sendRatingEmailToSupplier(Inquiry $inquiry, User $supplier): void
    {
        // Build finish inquiry link and sign it.
        $ratingUrl = $this->router->generate("inquiries/supplier-rating", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new TemplatedEmail())
            ->htmlTemplate('email/inquiry/rating/available_supplier.html.twig')
            ->subject($this->translator->trans("ratings.inquiring_rating_available"))
            ->to($supplier->getEmail());

        $this->sendRatingEmail($email, $inquiry, $supplier, $ratingUrl);
    }

    /**
     * Sends notification that inquiry rating is available for the inquiring.
     * @param Inquiry $inquiry
     */
    private function sendRatingEmailToInquiring(Inquiry $inquiry): void
    {
        // Build finish inquiry link and sign it.
        $ratingUrl = $this->router->generate("inquiries/finish-signed", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new TemplatedEmail())
            ->htmlTemplate('email/inquiry/rating/available_inquiring.html.twig')
            ->subject($this->translator->trans("ratings.supplier_rating_available"))
            ->to($inquiry->getContactEmail());

        $this->sendRatingEmail($email, $inquiry, $inquiry->getAuthor(), $ratingUrl);
    }

    /**
     * Signs $url and sends it in the email.
     * Create a new SignedRequest object to allow user access the link correctly.
     * @param TemplatedEmail $email
     * @param Inquiry $inquiry
     * @param User|null $user
     * @param string $url
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function sendRatingEmail(TemplatedEmail $email, Inquiry $inquiry, ?User $user, string $url)
    {
        $ratingExpiration = $this->params->get("app.inquiries.rating_link_expiration");
        $expireAt = (new DateTime("now + $ratingExpiration seconds"));
        $signedUrl = $this->urlSigner->sign($url, $expireAt);

        $email->from(new Address($this->params->get("app.email"), $this->params->get("app.name")))
            ->context(compact("inquiry", "signedUrl"));

        $this->mailer->send($email);

        // Save the request.
        $request = (new InquirySignedRequest())->setInquiry($inquiry)->setUser($user)
            ->setExpireAt($expireAt)->setToken($this->getTokenFromSignedUrl($signedUrl));

        $this->inquirySignedRequestService->create($request);
    }

    /**
     * Finished the inquiry in the request.
     * Sets the finished state and removes the request.
     * @param InquirySignedRequest $request
     */
    public function finishInquiryByRequest(InquirySignedRequest $request)
    {
        $this->finishInquiry($request->getInquiry());

        // Delete the request to prevent multiple times access..
        $this->inquirySignedRequestService->delete($request);
    }

    /**
     * Marks the inquiry as finished.
     * Sends notice to supplier if the supplier is filled in InquiringRating.
     * @param Inquiry $inquiry
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function finishInquiry(Inquiry $inquiry)
    {
        $rating = $inquiry->getInquiringRating();

        // Supplier is set
        if ($rating->getSupplier()) {
            // TODO: Do not send the email if the supplier has already rated the inquiry.
            // We want to send an email to the supplier to fill his rating form.
            $this->sendRatingEmailToSupplier($rating->getInquiry(), $rating->getSupplier());
        } // If supplier is not set
        else {
            // We do not want to store these field because supplier is not set.
            $rating->setRating(null);
            $rating->setSupplierNote(null);
        }

        // Update the state.
        $inquiry->setState(InquiryState::STATE_FINISHED);

        // Update the inquiry - the InquiringRating object is saved automatically.
        $this->inquiryService->update($inquiry);
    }

    public function createSupplierRatingByRequest(InquirySignedRequest $request): SupplierRating
    {
        // The request user must be filled so we know who is the author.
        // Now we ignore if the user is authorized because he could be authorizes as someone else.
        if (!$request->getUser()) {
            throw new LogicException("The request user can not be null!");
        }

        return (new SupplierRating())->setAuthor($request->getUser())->setInquiry($request->getInquiry());
    }

    /**
     * Creates and returns an object for rating an inquiry and its author.
     * @param Inquiry $inquiry
     * @return SupplierRating
     */
    public function createSupplierRating(Inquiry $inquiry): SupplierRating
    {
        if (!$this->security->isLoggedIn()) {
            throw new UnauthorizedHttpException("You are not authorized to do that");
        }

        return (new SupplierRating())->setAuthor($this->security->getUser())->setInquiry($inquiry);
    }

    /**
     * Saves suppliers rating by
     * @param InquirySignedRequest $request
     * @param SupplierRating $rating
     */
    public function saveSupplierRating(InquirySignedRequest $request, SupplierRating $rating): void
    {
        // Update the rating
        $this->supplierRatingService->create($rating);

        // Send notice to the inquiring user if the user has not rated the inquiry yet and the supplier realized the inquiry.
        if ($rating->getRealizedInquiry() && !$request->getInquiry()->getInquiringRating()) {
            $this->sendRatingEmailToInquiring($request->getInquiry());
        }

        // Delete the request
        $this->inquirySignedRequestService->delete($request);
    }
}