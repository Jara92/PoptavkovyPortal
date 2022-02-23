<?php

namespace App\Factory;

use App\Entity\Profile;

class ProfileFactory
{
    public function createPublicProfile(): Profile
    {
        return (new Profile())->setIsPublic(true);
    }
}