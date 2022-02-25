<?php

namespace App\Business\Operation;

use App\Business\Service\ProfileService;
use App\Business\Service\UserService;
use App\Entity\Inquiry\Subscription;
use App\Entity\Profile;
use App\Entity\User;
use App\Enum\Entity\UserType;
use App\Enum\Entity\UserRole;
use App\Exception\InvalidOldPasswordException;
use App\Exception\OperationFailedException;
use App\Factory\ProfileFactory;
use App\Form\User\CompanySettingsForm;
use App\Form\User\PersonSettingsForm;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserOperation
{
    public function __construct(
        private UserService                 $userService,
        private ProfileService              $profileService,
        private UserPasswordHasherInterface $passwordHasher,
        private ProfileFactory              $profileFactory
    )
    {
    }

    /**
     * Register a new system user.
     * @param User $user
     * @param string $blankPassword
     * @return bool
     */
    public function register(User $user, string $blankPassword): bool
    {
        // Set roles
        $this->addNewUserRoles($user);

        // Set password
        $passwordHash = $this->passwordHasher->hashPassword($user, $blankPassword);
        $user->setPassword($passwordHash);

        // Create a public profile
        $user->setProfile($this->profileFactory->createPublicProfile());

        $user->setSubscription(new Subscription());

        return $this->userService->create($user);
    }

    /**
     * Add default roles to a new user.
     * @param User $user
     */
    private function addNewUserRoles(User $user)
    {
        switch ($user->getType()) {
            case UserType::PERSON:
                $user->addRole(UserRole::INQUIRING);
                break;

            case UserType::COMPANY:
                $user->addRole(UserRole::SUPPLIER);
        }
    }

    /**
     * Returns class name of a form for the given user type.
     * @param User $user
     * @return string
     */
    public function getUserSettingsFormClass(User $user): string
    {
        if ($user->isType(UserType::PERSON)) {
            return PersonSettingsForm::class;
        } else if ($user->isType(UserType::COMPANY)) {
            return CompanySettingsForm::class;
        }

        throw new \LogicException("Unknown user type.");
    }

    /**
     * Updates users password and checks if old passwords match.
     * @param User $user
     * @param string $plainOldPassword
     * @param string $plainNewPassword
     * @throws InvalidOldPasswordException
     * @throws OperationFailedException
     */
    public function updateUserPassword(User $user, string $plainOldPassword, string $plainNewPassword)
    {
        // Check if old password match current user's password.
        if (!$this->passwordHasher->isPasswordValid($user, $plainOldPassword)) {
            throw new InvalidOldPasswordException();
        }

        // Hash the new password a set the has to the user.
        $passwordHash = $this->passwordHasher->hashPassword($user, $plainNewPassword);
        $user->setPassword($passwordHash);

        // Try to update the entity.
        if (!$this->userService->update($user)) {
            throw new OperationFailedException();
        }
    }
}