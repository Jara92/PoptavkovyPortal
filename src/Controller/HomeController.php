<?php

namespace App\Controller;

use App\Helper\FlashHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function home()
    {
        return $this->render("home/home.html.twig");
    }

    public function inquiring()
    {
        return $this->render("home/inquiring.html.twig");
    }

    public function supplier()
    {
        return $this->render("home/suppliers.html.twig");
    }

    public function howItWorks()
    {
        return $this->render("home/how-it-works.html.twig");
    }

    public function termsAndConditions()
    {
        return $this->render("home/terms-and-conditions.html.twig");
    }
}