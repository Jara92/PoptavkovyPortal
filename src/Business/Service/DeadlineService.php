<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Deadline;
use App\Repository\Interfaces\Inquiry\IDeadlineRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<Deadline, int>
 */
class DeadlineService extends AService
{
    /** @var IDeadlineRepository */
    protected IRepository $repository;

    public function __construct(IDeadlineRepository $deadlineRepository)
    {
        parent::__construct($deadlineRepository);
    }
}