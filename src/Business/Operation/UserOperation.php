<?php

namespace App\Business\Operation;

use App\Business\Service\ProfileService;
use App\Business\Service\UserService;
use App\Entity\Profile;
use App\Entity\User;
use App\Entity\UserType;
use App\Exception\InvalidOldPasswordException;
use App\Exception\OperationFailedException;
use App\Form\User\CompanySettingsForm;
use App\Form\User\PersonSettingsForm;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserOperation
{
    public function __construct(
        private UserService                 $userService,
        private ProfileService              $profileService,
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

        // Set password
        $passwordHash = $this->passwordHasher->hashPassword($user, $blankPassword);
        $user->setPassword($passwordHash);

        // Create a blank profile
        $user->setProfile((new Profile()));

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

    public function updateUserPassword(User $user, string $plainOldPassword, string $plainNewPassword)
    {
        if (!$this->passwordHasher->isPasswordValid($user, $plainOldPassword)) {
            throw new InvalidOldPasswordException();
        }

        $passwordHash = $this->passwordHasher->hashPassword($user, $plainNewPassword);
        $user->setPassword($passwordHash);

        if (!$this->userService->update($user)) {
            throw new OperationFailedException();
        }
    }
}