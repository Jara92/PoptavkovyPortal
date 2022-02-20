<?php

namespace App\Controller;

use App\Business\Operation\UserOperation;
use App\Business\Service\UserService;

class UserController extends AController
{
    public function __construct(
        private UserOperation $userOperation,
        private UserService   $userService
    )
    {
    }

    public function myProfile()
    {
        return $this->render("user/base.html.twig");
    }
}