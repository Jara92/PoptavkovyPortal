<?php

namespace App\Controller\Admin;

use App\Business\Service\InquiryStateService;
use App\Controller\Admin\Inquiry\NewInquiriesCrudController;
use App\Entity\Inquiry\Deadline;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquiryCategory;
use App\Entity\Inquiry\InquiryState;
use App\Entity\Inquiry\InquiryType;
use App\Entity\Inquiry\InquiryValue;
use App\Entity\Inquiry\Region;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

class MainDashboardController extends AbstractDashboardController
{
    public function __construct(
        private InquiryStateService $inquiryStateService
    )
    {
    }

    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/main_dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Poptejsi.cz')
            ->setTranslationDomain("messages");
    }

    public function configureMenuItems(): iterable
    {
        $newState = $this->inquiryStateService->readByAlias(InquiryState::STATE_NEW);

        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::subMenu("inquiries.inquiries", "fa fa-tags")->setSubItems([
                MenuItem::linkToCrud("inquiries.inquiries_filter_all", "fa fa-tags", Inquiry::class),
                MenuItem::linkToCrud("inquiries.inquiries_filter_new", "fa fa-folder-plus", Inquiry::class)
                    ->setQueryParameter("filters[state]", ["comparison" => "=", "value" => $newState->getId()])
            ]),
            MenuItem::linkToCrud("inquiries.inquiry_categories", "fa fa-tags", InquiryCategory::class),
            MenuItem::linkToCrud("inquiries.inquiry_types", "fa fa-tags", InquiryType::class),
            MenuItem::linkToCrud("inquiries.inquiry_values", "fa fa-tags", InquiryValue::class),
            MenuItem::linkToCrud("inquiries.deadlines", "fa fa-clock", Deadline::class),
            MenuItem::linkToCrud("inquiries.regions", "fa", Region::class)
        ];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
