<?php

namespace App\Business\Service;

class UserService
{
    public function getCurrentUser()
    {
        return null;
    }

    public function isCompany($user = null): bool
    {
        if (!$user) {
            $user = $this->getCurrentUser();

            // User is not logged in.
            if ($user) {
                return false;
            }
        }

        // TODO: add logic for company/person detection

        return false;
    }
}