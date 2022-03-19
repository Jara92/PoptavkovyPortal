<?php

namespace App\Controller;

use App\Business\Service\Inquiry\InquiryCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function __construct(
        private InquiryCategoryService $inquiryCategoryService,
    )
    {
    }

    public function home(): Response
    {
        $categories = $this->inquiryCategoryService->readAllRootCategories();

        return $this->render("home/home.html.twig", compact("categories"));
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