<?php

namespace App\Business\Operation;

use App\Business\Service\UserService;
use App\Entity\User;
use App\Entity\UserType;
use App\Form\User\CompanySettingsForm;
use App\Form\User\PersonSettingsForm;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserOperation
{
    public function __construct(
        private UserService                 $userService,
        private UserPasswordHasherInterface $passwordHasher
    )
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

    public function getUserSettingsFormClass(User $user): string
    {
        if ($user->isType(UserType::TYPE_PERSONAL)) {
            return PersonSettingsForm::class;
        } else if ($user->isType(UserType::TYPE_COMPANY)) {
            return CompanySettingsForm::class;
        }

        throw new \LogicException("Unknown user type.");
    }

    public function userPasswordMatch(User $user, string $plainPassword): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $plainPassword);
    }

    public function updateUserPassword(User $user, string $plainPassword)
    {
        $passwordHash = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($passwordHash);

        return $this->userService->update($user);
    }
}