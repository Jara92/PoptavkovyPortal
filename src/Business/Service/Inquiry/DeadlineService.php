<?php

namespace App\Business\Service\Inquiry;

use App\Business\Service\AService;
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

    /**
     * Returns Deadline corresponding to the given string.
     * @param string $value
     * @return Deadline|null
     */
    public function figureOut(string $value): ?Deadline
    {
        return $this->repository->figureOut($value);
    }
}