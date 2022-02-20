<?php

namespace App\Controller;

use App\Business\Operation\UserOperation;
use App\Business\Service\UserService;

class ProfileController extends AController
{
    public function __construct(
        private UserOperation $userOperation,
        private UserService   $userService
    )
    {
    }
}