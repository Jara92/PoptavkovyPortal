<?php

namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    public function createBlank(): User
    {
        return new User();
    }

    public function createPersonalUser(string $name, string $surname, string $email, string $phone, string $password, $createdAt, $roles): User
    {
        $newUser = new User();

        // TODO: Use PersonUser instead
        return $newUser->setEmail($email)->setPhone($phone)->setPassword($password)->setCreatedAt($createdAt)->setUpdatedAt($createdAt)->setRoles($roles);
    }
}