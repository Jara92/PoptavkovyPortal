<?php

namespace App\Repository\Interfaces\User;

use App\Entity\User\Rating;
use App\Repository\Interfaces\IRepository;

/**
 * @implements IRepository<Rating, int>
 */
interface IRatingRepository extends IRepository
{
}