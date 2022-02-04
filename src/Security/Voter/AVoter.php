<?php

namespace App\Security\Voter;

use App\Business\Service\UserService;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

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

    /** @required */
    public Security $security;

    /** @required  */
    public UserService $userService;
}