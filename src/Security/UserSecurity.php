<?php

namespace App\Security;

use App\Entity\User;
use App\Enum\Entity\UserRole;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class UserSecurity
{
    /** @required */
    public Security $security;

    /**
     * Returns currently logged user or null.
     * @return User|null
     */
    public function getUser(): ?User
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
        return $this->security->getUser() !== null;
    }

    /**
     * Checks if the role are granted against the current authentication token and optionally supplied subject.
     * @param UserRole $role
     * @param mixed|null $subject
     * @return bool
     */
    public function isGranted(UserRole $role, mixed $subject = null): bool
    {
        return $this->security->isGranted($role->value, $subject);
    }

    public function getToken(): ?TokenInterface
    {
        return $this->security->getToken();
    }
}