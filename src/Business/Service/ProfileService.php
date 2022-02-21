<?php

namespace App\Business\Service;

use App\Entity\Profile;
use App\Repository\Interfaces\User\IProfileRepository;
use App\Repository\Interfaces\IRepository;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<Profile, int>
 */
class ProfileService extends AService
{
    /** @var IProfileRepository */
    protected IRepository $repository;

    public function __construct(IProfileRepository $profileRepository)
    {
        parent::__construct($profileRepository);
    }
}