<?php

namespace App\Business\Operation;

use App\Business\Service\CompanyContactService;
use App\Business\Service\InquiryService;
use App\Business\Service\InquiryStateService;
use App\Business\Service\InquiryTypeService;
use App\Business\Service\UserService;
use App\Entity\Company;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquiryState;
use App\Entity\Inquiry\InquiryType;
use App\Entity\Person;
use App\Entity\User;
use App\Entity\UserType;
use App\Exception\InvalidInquiryState;
use App\Factory\Inquiry\CompanyContactFactory;
use App\Factory\Inquiry\PersonalContactFactory;
use App\Helper\UrlHelper;
use App\Security\UserSecurity;
use Exception;
use LogicException;

class InquiryOperation
{
    protected UserService $userService;

    protected InquiryService $inquiryService;

    protected InquiryTypeService $inquiryTypeService;

    /** @required */
    public PersonalContactFactory $personalContactFactory;

    /** @required */
    public CompanyContactFactory $companyContactFactory;

    /** @required */
    public CompanyContactService $companyContactService;

    /** @required */
    public InquiryStateService $inquiryStateService;

    /** @required */
    public UserSecurity $security;

    public function __construct(InquiryService $inquiryService, InquiryTypeService $inquiryTypeService, UserService $userService)
    {
        $this->inquiryService = $inquiryService;
        $this->userService = $userService;
        $this->inquiryTypeService = $inquiryTypeService;
    }

    public function getNewInquiryDefaultType(): ?InquiryType
    {
        $user = $this->security->getUser();

        // According to userType set default Inquiry type.
        $userType = $user ? $user->getType()->getAlias() : "";

        switch ($userType) {
            case UserType::TYPE_COMPANY:
                $typeAlias = InquiryType::ALIAS_COMPANY;
                break;
            case UserType::TYPE_PERSONAL:
                $typeAlias = InquiryType::ALIAS_PERSONAL;
                break;
            default:
                $typeAlias = InquiryType::ALIAS_PERSONAL;
        }

        return $this->inquiryTypeService->getInquiryTypeByAlias($typeAlias);
    }

    /**
     * @throws Exception
     */
    public function createInquiry(Inquiry $inquiry): bool
    {
        // Remove useless contact object.
        switch ($inquiry->getType()->getAlias()) {
            case  InquiryType::ALIAS_PERSONAL:
                $inquiry->setCompanyContact(null);
                break;
            case InquiryType::ALIAS_COMPANY:
                $inquiry->setPersonalContact(null);
                break;
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
        $inquiry->setAlias(UrlHelper::createAlias($inquiry->getId(), $inquiry->getTitle()));
        $this->inquiryService->update($inquiry);

        return true;
    }

    /**
     * Fill inquiry contact data by User information.
     * @param Inquiry $inquiry
     * @param User|null $user
     * @return Inquiry
     */
    public function fillUserData(Inquiry $inquiry, ?User $user): Inquiry
    {
        // No autofill for unauthenticated user.
        if (is_null($user)) {
            return $inquiry;
        }

        // Common user data
        $inquiry->setContactEmail($user->getEmail());
        $inquiry->setContactPhone($user->getPhone());

        // Personal user
        if ($user->isType(UserType::TYPE_PERSONAL)) {
            return $this->fillPersonData($inquiry, $user->getPerson());
        }
        // Company user
        else if($user->isType(UserType::TYPE_COMPANY)){
            return $this->fillCompanyData($inquiry, $user->getCompany());
        }

        throw new LogicException("Unknown user type: " . $user->getType()->getAlias());
    }

    protected function fillPersonData(Inquiry $inquiry, ?Person $person): Inquiry
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

    protected function fillCompanyData(Inquiry $inquiry, ?Company $company): Inquiry
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