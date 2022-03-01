<?php

namespace App\Repository\Interfaces\Inquiry;

use App\Entity\Inquiry\Deadline;
use App\Repository\Interfaces\IRepository;

/**
 * @implements IRepository<Deadline, int>
 */
interface IDeadlineRepository extends IRepository
{
    /**
     * Returns Deadline corresponding to the given string.
     * @param string $value
     * @return Deadline|null
     */
    public function figureOut(string $value): ?Deadline;
}