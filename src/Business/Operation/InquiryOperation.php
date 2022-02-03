<?php

namespace App\Business\Operation;

use App\Business\Service\InquiryService;
use App\Business\Service\InquiryTypeService;
use App\Business\Service\UserService;
use App\Entity\Inquiry\InquiryType;

class InquiryOperation
{
    protected $userService;

    protected $inquiryService;

    protected $inquiryTypeService;

    public function __construct(InquiryService $inquiryService, InquiryTypeService $inquiryTypeService, UserService $userService){
        $this->inquiryService = $inquiryService;
        $this->userService = $userService;
        $this->inquiryTypeService = $inquiryTypeService;
    }

    public function getNewInquiryDefaultType(){
        if($this->userService->isCompany()){
            $typeAlias = InquiryType::ALIAS_COMPANY;
        }
        else{
            $typeAlias = InquiryType::ALIAS_PERSONAL;
        }

        return $this->inquiryTypeService->getInquiryTypeByAlias($typeAlias);
    }
}