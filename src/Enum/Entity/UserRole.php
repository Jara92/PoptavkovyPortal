<?php

namespace App\Enum\Entity;

enum UserRole: string
{
    // Can do anything
    case SUPER_ADMIN = "ROLE_SUPER_ADMIN";
    // Can access admin site
    case ADMIN = "ROLE_ADMIN";
    // Can post new inquiries.
    case INQUIRING = "ROLE_INQUIRING";
    // Can respond to inquiries.
    case SUPPLIER = "ROLE_SUPPLIER";
    // Can access features for logged-in users.
    case VERIFIED = "ROLE_VERIFIED";
    // Logged in user
    case USER = "ROLE_USER";
}