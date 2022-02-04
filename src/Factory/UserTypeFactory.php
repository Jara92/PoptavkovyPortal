<?php

namespace App\Factory;

use App\Entity\UserType;

class UserTypeFactory
{
    public function createUserType(string $title, string $alias): UserType
    {
        $userType = new UserType();

        return $userType->setTitle($title)->setAlias($alias);
    }
}