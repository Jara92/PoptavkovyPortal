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

    /** @required  */
    public Security $security;

    public function __construct(IUserRepository $userRepository, UserFactory $userFactory)
    {
        parent::__construct($userRepository);
        $this->userFactory = $userFactory;
    }

    public function getCurrentUser(): ?User
    {
       $user =  $this->security->getUser();
       if($user instanceof User){
           return $user;
       }

       return null;
    }

    public function isLoggedIn():bool{
        return $this->getCurrentUser() !== null;
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