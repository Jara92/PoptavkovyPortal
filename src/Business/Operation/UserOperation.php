<?php

namespace App\Business\Operation;

use App\Business\Service\UserService;
use App\Business\Service\UserTypeService;
use App\Entity\User;
use App\Entity\UserType;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class UserOperation
{
    protected $userService;

    protected $passwordHasher;

    /** @var EmailVerifier */
    protected $emailVerifier;

    public function __construct(UserService $userService, UserPasswordHasherInterface $passwordHasher, EmailVerifier $emailVerifier)
    {
        $this->userService = $userService;
        $this->passwordHasher = $passwordHasher;
        $this->emailVerifier = $emailVerifier;
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