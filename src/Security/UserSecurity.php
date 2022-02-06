<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class UserSecurity extends Security
{
    /**
     * Returns currently logged user or null.
     * @return User|null
     */
    public function getUser(): ?User
    {
        $user = parent::getUser();

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
        return $this->getUser() !== null;
    }
}