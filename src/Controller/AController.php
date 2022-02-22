<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\FlashMessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @method User getUser()
 */
class AController extends AbstractController
{
    protected function addFlashMessage(FlashMessageType $type, string $text)
    {
        $this->addFlash($type->value, $text);
    }
}