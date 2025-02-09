<?php

namespace App\Controller;

use App\Business\Service\Inquiry\InquiryCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class HomeController extends AbstractController
{
    public function __construct(
        private InquiryCategoryService $inquiryCategoryService,
        private Breadcrumbs            $breadcrumbs,
        private RouterInterface        $router
    )
    {
        $this->breadcrumbs->addItem("mainnav.home", $this->router->generate("home"));
    }

    public function home(): Response
    {
        // No breadcrumbs on home page
        $this->breadcrumbs->clear();

        $categories = $this->inquiryCategoryService->readAllRootCategories();

        return $this->render("home/home.html.twig", compact("categories"));
    }

    public function inquiring(): Response
    {
        $this->breadcrumbs->addItem("articles.how_it_works", $this->router->generate("how-it-works"));
        $this->breadcrumbs->addItem("articles.for_inquiring", $this->router->generate("inquiring"));

        return $this->render("home/inquiring.html.twig");
    }

    public function supplier(): Response
    {
        $this->breadcrumbs->addItem("articles.how_it_works", $this->router->generate("how-it-works"));
        $this->breadcrumbs->addItem("articles.suppliers", $this->router->generate("suppliers"));

        return $this->render("home/suppliers.html.twig");
    }

    public function cookiesAction(): Response
    {
        // FIXME: for now we will just redirect the user
        return $this->redirect('https://www.cookie-lista.cz/co-je-cookies.html');

        $this->breadcrumbs->addItem("cookies.cookies", $this->router->generate("cookies"));

        return $this->render("home/cookies.html.twig");
    }

    public function howItWorks(): Response
    {
        $this->breadcrumbs->addItem("articles.how_it_works", $this->router->generate("how-it-works"));
        return $this->render("home/how-it-works.html.twig");
    }

    public function termsAndConditions(): Response
    {
        return $this->render("home/terms-and-conditions.html.twig");
    }
}