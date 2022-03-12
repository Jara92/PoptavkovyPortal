<?php

namespace App\Business\Service\Inquiry;

use App\Business\Service\AService;
use App\Entity\Inquiry\PersonalContact;
use App\Repository\Interfaces\Inquiry\IPersonalContactRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<PersonalContact, int>
 */
class PersonalContactService extends AService
{
    /** @var IPersonalContactRepository */
    protected IRepository $repository;

    public function __construct(IPersonalContactRepository $inquiryRepository)
    {
        parent::__construct($inquiryRepository);
    }
}