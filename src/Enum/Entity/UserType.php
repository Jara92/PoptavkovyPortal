<?php

namespace App\Enum\Entity;

enum UserType: string
{
    case PERSON = "person";
    case COMPANY = "company";
}