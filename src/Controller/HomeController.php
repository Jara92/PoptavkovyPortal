<?php

namespace App\Controller;

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
}