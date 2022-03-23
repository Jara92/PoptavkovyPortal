<?php

namespace App\Security\Voter;

use App\Security\UserSecurity;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * NOTE: For better performance use:
 * https://github.com/symfony/symfony/blob/6.0/src/Symfony/Component/Security/Core/Authorization/Voter/CacheableVoterInterface.php
 */
abstract class AVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';
    const DELETE = 'delete';

    #[Required]
    public UserSecurity $security;
}