<?php

namespace App\Business\Service;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\Interfaces\IRepository;
use App\Repository\Interfaces\IUserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

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

    /** @required */
    public Security $security;

    public function __construct(IUserRepository $userRepository, UserFactory $userFactory)
    {
        parent::__construct($userRepository);
        $this->userFactory = $userFactory;
    }

    /**
     * Returns currently logged user or null.
     * @return User|null
     */
    public function getCurrentUser(): ?User
    {
        $user = $this->security->getUser();

        // Is user valid?
        if ($user instanceof User) {
            return $user;
        }

        return null;
    }

    /**
     * Is the user logged in?
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->getCurrentUser() !== null;
    }
}