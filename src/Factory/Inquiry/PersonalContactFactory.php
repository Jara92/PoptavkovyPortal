<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\PersonalContact;

class PersonalContactFactory
{
    public function createBlankPersonalContact(): PersonalContact
    {
        return new PersonalContact();
    }

    public function createPersonalContact(string $name, string $surname):PersonalContact{
        $personalContact = new PersonalContact();

        return $personalContact->setName($name)->setSurname($surname);
    }
}