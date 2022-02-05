<?php

namespace App\Business\Service;

use App\Entity\UserType;
use App\Repository\Interfaces\IRepository;
use App\Repository\Interfaces\IUserTypeRepository;

/**
 * Service to manager UserType.
 * @template E
 * @template K
 * @extends AService<UserType, int>
 */
class UserTypeService extends AService
{
    /** @var IUserTypeRepository */
    protected IRepository $repository;

    public function __construct(IUserTypeRepository $userRepository)
    {
        parent::__construct($userRepository);
    }

    public function readByAlias(string $alias){
        return $this->repository->findOneBy(["alias" => $alias]);
    }
}