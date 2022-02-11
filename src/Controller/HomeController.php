<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function home(): Response
    {
        return $this->render("home/home.html.twig");
    }

    public function inquiring(): Response
    {
        return $this->render("home/inquiring.html.twig");
    }

    public function supplier(): Response
    {
        return $this->render("home/suppliers.html.twig");
    }

    public function howItWorks(): Response
    {
        return $this->render("home/how-it-works.html.twig");
    }

    public function termsAndConditions(): Response
    {
        return $this->render("home/terms-and-conditions.html.twig");
    }
}