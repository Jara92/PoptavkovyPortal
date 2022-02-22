<?php

namespace App\Enum\Entity;

enum UserRole: string
{
    // Can do anything
    case ROLE_SUPER_ADMIN = "ROLE_SUPER_ADMIN";
    // Can access admin site
    case ROLE_ADMIN = "ROLE_ADMIN";
    // Can post new inquiries.
    case ROLE_INQUIRING = "ROLE_INQUIRING";
    // Can respond to inquiries.
    case ROLE_SUPPLIER = "ROLE_SUPPLIER";
    // Can access features for logged-in users.
    case ROLE_VERIFIED = "ROLE_VERIFIED";

    case ROLE_USER = "ROLE_USER";
}