<?php

namespace App\Business\Operation;

use App\Business\Service\CompanyContactService;
use App\Business\Service\InquiryService;
use App\Business\Service\InquiryStateService;
use App\Business\Service\InquiryTypeService;
use App\Business\Service\UserService;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquiryState;
use App\Entity\Inquiry\InquiryType;
use App\Exception\InvalidInquiryState;
use App\Helper\UrlHelper;
use Exception;

class InquiryOperation
{
    protected UserService $userService;

    protected InquiryService $inquiryService;

    protected InquiryTypeService $inquiryTypeService;

    /** @required */
    public CompanyContactService $companyContactService;

    /** @required */
    public InquiryStateService $inquiryStateService;

    public function __construct(InquiryService $inquiryService, InquiryTypeService $inquiryTypeService, UserService $userService)
    {
        $this->inquiryService = $inquiryService;
        $this->userService = $userService;
        $this->inquiryTypeService = $inquiryTypeService;
    }

    public function getNewInquiryDefaultType(): ?InquiryType
    {
        if ($this->userService->isCompany()) {
            $typeAlias = InquiryType::ALIAS_COMPANY;
        } else {
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
        $inquiry->setAuthor($this->userService->getCurrentUser());

        $this->inquiryService->create($inquiry);

        // Generate inquiry alias and update entity.
        $inquiry->setAlias(UrlHelper::createAlias($inquiry->getId(), $inquiry->getTitle()));
        $this->inquiryService->update($inquiry);

        return true;
    }
}