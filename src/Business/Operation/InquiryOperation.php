<?php

namespace App\Business\Operation;

use App\Business\Service\CompanyContactService;
use App\Business\Service\InquiryAttachmentService;
use App\Business\Service\InquiryService;
use App\Business\Service\InquiryStateService;
use App\Business\Service\InquiryTypeService;
use App\Business\Service\UserService;
use App\Entity\Company;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquiryAttachment;
use App\Entity\Inquiry\InquiryState;
use App\Entity\Inquiry\InquiryType;
use App\Entity\Person;
use App\Entity\User;
use App\Entity\UserType;
use App\Exception\InvalidInquiryState;
use App\Factory\Inquiry\CompanyContactFactory;
use App\Factory\Inquiry\InquiryAttachmentFactory;
use App\Factory\Inquiry\InquiryFactory;
use App\Factory\Inquiry\PersonalContactFactory;
use App\Factory\InquiryFilterFactory;
use App\Helper\UrlHelper;
use App\Security\UserSecurity;
use App\Tools\Filter\InquiryFilter;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class InquiryOperation
{
    protected UserService $userService;

    protected InquiryService $inquiryService;

    /** @required */
    public InquiryAttachmentService $attachmentService;

    protected InquiryStateService $inquiryStateService;

    protected InquiryTypeService $inquiryTypeService;

    protected InquiryFactory $inquiryFactory;

    protected InquiryAttachmentFactory $attachmentFactory;

    protected InquiryFilterFactory $filterFactory;

    protected PersonalContactFactory $personalContactFactory;

    protected CompanyContactFactory $companyContactFactory;

    protected UserSecurity $security;

    /** @required */
    public SluggerInterface $slugger;

    /** @required */
    // public Configuration $configuration;
    public ContainerBagInterface $params;

    /**
     * @param UserService $userService
     * @param InquiryService $inquiryService
     * @param InquiryStateService $inquiryStateService
     * @param InquiryTypeService $inquiryTypeService
     * @param InquiryFactory $inquiryFactory
     * @param InquiryAttachmentFactory $attachmentFactory
     * @param InquiryFilterFactory $filterFactory
     * @param PersonalContactFactory $personalContactFactory
     * @param CompanyContactFactory $companyContactFactory
     * @param UserSecurity $security
     */
    public function __construct(UserService $userService, InquiryService $inquiryService, InquiryStateService $inquiryStateService, InquiryTypeService $inquiryTypeService, InquiryFactory $inquiryFactory, InquiryAttachmentFactory $attachmentFactory, InquiryFilterFactory $filterFactory, PersonalContactFactory $personalContactFactory, CompanyContactFactory $companyContactFactory, UserSecurity $security)
    {
        $this->userService = $userService;
        $this->inquiryService = $inquiryService;
        $this->inquiryStateService = $inquiryStateService;
        $this->inquiryTypeService = $inquiryTypeService;
        $this->inquiryFactory = $inquiryFactory;
        $this->attachmentFactory = $attachmentFactory;
        $this->filterFactory = $filterFactory;
        $this->personalContactFactory = $personalContactFactory;
        $this->companyContactFactory = $companyContactFactory;
        $this->security = $security;
    }

    /**
     * Get default inquiry type for the user.
     * @return InquiryType|null
     */
    public function getNewInquiryDefaultType(): ?InquiryType
    {
        $user = $this->security->getUser();
        $typeAlias = InquiryType::ALIAS_PERSONAL;

        // According to userType set default Inquiry type.
        if ($user) {
            if ($user->isType(UserType::TYPE_PERSONAL)) {
                $typeAlias = InquiryType::ALIAS_PERSONAL;
            } else if ($user->isType(UserType::TYPE_COMPANY)) {
                $typeAlias = InquiryType::ALIAS_COMPANY;
            }
        }

        return $this->inquiryTypeService->getInquiryTypeByAlias($typeAlias);
    }

    /**
     * Returns an inquiry filter objects with default options.
     * @return InquiryFilter
     */
    public function getDefaultFilter(): InquiryFilter
    {
        // Default inquiry states visible for all users.
        $activeState = $this->inquiryStateService->readByAlias(InquiryState::STATE_ACTIVE);
        $archivedState = $this->inquiryStateService->readByAlias(InquiryState::STATE_ARCHIVED);

        return $this->filterFactory->createBlankInquiryFilter()->setStates([$activeState, $archivedState]);
    }

    /**
     * Create a new inquiry.
     * @param Inquiry $inquiry
     * @param UploadedFile[] $attachments
     * @return bool
     * @throws InvalidInquiryState
     */
    public function createInquiry(Inquiry $inquiry, array $attachments = []): bool
    {
        // Remove useless contact object.
        if ($inquiry->isType(InquiryType::ALIAS_PERSONAL)) {
            $inquiry->setCompanyContact(null);
        } else if ($inquiry->isType(InquiryType::ALIAS_COMPANY)) {
            $inquiry->setPersonalContact(null);
        }

        // Set state
        $state = $this->inquiryStateService->readByAlias(InquiryState::STATE_NEW);

        if (is_null($state)) {
            throw new InvalidInquiryState("Unknown state alias = " . InquiryState::STATE_NEW);
        }

        $inquiry->setState($state);

        // Set inquiry author.
        $inquiry->setAuthor($this->security->getUser());

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
     * @param Inquiry $inquiry
     * @param UploadedFile[] $attachments
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function saveAttachments(Inquiry $inquiry, array $attachments)
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
        $inquiry->setType($this->getNewInquiryDefaultType());

        // No autofill for unauthenticated user.
        if (is_null($user)) {
            return $inquiry;
        }

        // Returns inquiry and autofilled data.
        return $this->fillContactData($inquiry, $user);
    }

    protected function fillContactData(Inquiry $inquiry, User $user): Inquiry
    {
        // Common user data
        $inquiry->setContactEmail($user->getEmail());
        $inquiry->setContactPhone($user->getPhone());

        // Personal user
        if ($user->isType(UserType::TYPE_PERSONAL)) {
            return $this->fillPersonContactData($inquiry, $user->getPerson());
        } // Company user
        else if ($user->isType(UserType::TYPE_COMPANY)) {
            return $this->fillCompanyContactData($inquiry, $user->getCompany());
        }

        throw new LogicException("Unknown user type: " . $user->getType()->getAlias());
    }

    protected function fillPersonContactData(Inquiry $inquiry, ?Person $person): Inquiry
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

    protected function fillCompanyContactData(Inquiry $inquiry, ?Company $company): Inquiry
    {
        // Check if person is valid.
        if (is_null($company)) {
            throw new LogicException("User.person must not be null for this type of user!");
        }

        // Create new company contact instance.
        $companyContact = $this->companyContactFactory->createCompanyContact($company->getName());
        $inquiry->setCompanyContact($companyContact);

        return $inquiry;
    }
}