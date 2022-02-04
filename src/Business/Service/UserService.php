<?php

namespace App\Business\Service;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\Interfaces\IRepository;
use App\Repository\Interfaces\IUserRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Abstract service which implements basic service features.
 * @template E
 * @template K
 * @extends AService<User, int>
 */
class UserService extends AService
{
    protected UserFactory $userFactory;

    /** @var IUserRepository */
    protected IRepository $repository;

    public function __construct(IUserRepository $userRepository, UserFactory $userFactory)
    {
        parent::__construct($userRepository);
        $this->userFactory = $userFactory;
    }

    public function getCurrentUser()
    {
        // TODO: Implement
        return null;
    }

    public function isCompany($user = null): bool
    {
        if (!$user) {
            $user = $this->getCurrentUser();

            // User is not logged in.
            if (!$user) {
                return false;
            }
        }

        // TODO: add logic for company/person detection

        return false;
    }
}