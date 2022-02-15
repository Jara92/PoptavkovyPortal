<?php

namespace App\Business\Operation;

use App\Business\Service\UserService;
use App\Entity\User;
use App\Entity\UserType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserOperation
{
    public function __construct(
        private UserService $userService, private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function register(User $user, string $blankPassword): bool
    {
        // Set roles
        switch ($user->getType()->getAlias()) {
            case UserType::TYPE_PERSONAL:
                $user->addRole(User::ROLE_INQUIRING);
                break;

            case UserType::TYPE_COMPANY:
                $user->addRole(User::ROLE_SUPPLIER);
        }

        $passwordHash = $this->passwordHasher->hashPassword($user, $blankPassword);

        $user->setPassword($passwordHash);

        return $this->userService->create($user);
    }
}