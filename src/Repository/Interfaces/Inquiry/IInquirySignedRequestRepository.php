<?php

namespace App\Repository\Interfaces\Inquiry;

use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\Inquiry;
use App\Repository\Interfaces\IRepository;

/**
 * @implements IRepository<Inquiry, int>
 */
interface IInquirySignedRequestRepository extends IRepository
{
}