<?php

namespace App\Business\Operation;

use App\Business\Service\UserService;
use App\Entity\User;
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

    public function __construct(UserService $userService, UserPasswordHasherInterface $passwordHasher, EmailVerifier $emailVerifier){
        $this->userService = $userService;
        $this->passwordHasher = $passwordHasher;
        $this->emailVerifier = $emailVerifier;
    }

    public function register(User $user, string $blankPassword): bool
    {
        $passwordHash = $this->passwordHasher->hashPassword($user, $blankPassword);

        $now = new \DateTime();
        $user->setCreatedAt($now)->setUpdatedAt($now);

        $user->setPassword($passwordHash);

        return $this->userService->create($user);
    }
}