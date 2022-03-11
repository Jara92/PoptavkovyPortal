<?php

namespace App\Business\Operation;

use App\Business\Service\DeadlineService;
use App\Business\Service\InquiryAttachmentService;
use App\Business\Service\InquiryService;
use App\Business\Service\InquiryValueService;
use App\Business\Service\OfferService;
use App\Business\Service\SmartTagService;
use App\Entity\Company;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\Offer;
use App\Enum\Entity\InquiryState;
use App\Enum\Entity\InquiryType;
use App\Entity\Person;
use App\Entity\User;
use App\Enum\Entity\UserType;
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
use DateTime;
use LogicException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class InquiryOperation
{
    public function __construct(
        private InquiryService           $inquiryService,
        private InquiryAttachmentService $attachmentService,
        private InquiryValueService      $inquiryValueService,
        private DeadlineService          $deadlineService,
        private SubscriptionOperation    $subscriptionOperation,
        private InquiryFactory           $inquiryFactory,
        private InquiryAttachmentFactory $attachmentFactory,
        private InquiryFilterFactory     $filterFactory,
        private OfferService             $offerService,
        private OfferFactory             $offerFactory,
        private SmartTagService          $smartTagService,
        private PersonalContactFactory   $personalContactFactory,
        private CompanyContactFactory    $companyContactFactory,
        private UserSecurity             $security,
        private SluggerInterface         $slugger,
        private ContainerBagInterface    $params,
        private TranslatorInterface      $translator,
        private MailerInterface          $mailer
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

    public function createOffer(Inquiry $inquiry): Offer
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException("Use must be authorized to do this!");
        }

        return $this->offerFactory->createOffer($user, $inquiry);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendOffer(Offer $offer, bool $sendCopy = false)
    {
        $this->offerService->create($offer);

        // Notification email to the inquiry author.
        $this->sendOfferEmailToInquiring($offer);

        // Send copy to offer author.
        if ($sendCopy) {
            $this->sendOfferEmailToSupplier($offer);
        }
    }

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
            ->to($offer->getInquiry()->getAuthor()->getEmail())
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

            // Set remove notification date
            $notificationSeconds = $this->params->get("app.inquiries.auto_remove_notification_delay");
            $notificationAt = new DateTime("+ $notificationSeconds seconds");
            $inquiry->setRemoveNoticeAt($notificationAt);

            // Set actual removing date
            $removeSeconds = $notificationSeconds + $this->params->get("app.inquiries.auto_remove_delay");
            $removeAt = new DateTime("+ $removeSeconds seconds");
            $inquiry->setRemoveAt($removeAt);

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
        $email = (new TemplatedEmail())
            ->from(new Address($this->params->get("app.email"), $this->params->get("app.name")))
            ->to($inquiry->getAuthor()->getEmail())
            ->subject($this->translator->trans("inquiries.inquiry_will_be_removed"))
            ->htmlTemplate('email/inquiry/expiration_notify.html.twig')
            ->context(["inquiry" => $inquiry]);

        $this->mailer->send($email);

        // Remove next notification date
        $inquiry->setRemoveNoticeAt(null);
        $this->inquiryService->update($inquiry);
    }

    private function autoRemove(Inquiry $inquiry): void
    {

    }
}