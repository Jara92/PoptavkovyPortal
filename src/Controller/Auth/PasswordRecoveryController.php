<?php

namespace App\Controller\Auth;

use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Symfony\Component\HttpFoundation\Request;

class PasswordRecoveryController
{
    public function recoverPassword(Request $request){
        // TODO: implement
        throw new FeatureNotImplemented("This action is not implemented yet.");
    }
}