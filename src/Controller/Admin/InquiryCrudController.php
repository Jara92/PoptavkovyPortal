<?php

namespace App\Controller\Admin;

use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\Deadline;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\PersonalContact;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InquiryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Inquiry::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    public function new(AdminContext $context): \EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        $out = parent::new($context);

        /** @var Inquiry $inquiry */
        $inquiry = $context->getEntity()->getInstance();
        $now = new DateTime();
        $inquiry->setCreatedAt($now)->setUpdatedAt($now);

        return $out;
    }


    /*
     * protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
    {
        $submitButtonName = $context->getRequest()->request->all()['ea']['newForm']['btn'];

        if ('saveAndViewDetail' === $submitButtonName) {
            $url = $this->get(AdminUrlGenerator::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($context->getEntity()->getPrimaryKeyValue())
                ->generateUrl();

            return $this->redirect($url);
        }

        return parent::getRedirectResponseAfterSave($context, $action);
    }
     */


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab("admin.inquiries.title_common")->onlyOnForms(),
            FormField::addPanel("admin.inquiries.title_common")->onlyOnForms(),

            TextField::new('title', "inquiries.field_title"),
            TextField::new('alias', "inquiries.field_alias"),

            TextareaField::new('description', "inquiries.field_description")->hideOnIndex(),

            AssociationField::new("region", "inquiries.field_region")
                ->setFormTypeOptions(['choice_label' => "title", "choice_translation_domain" => "messages"]),

            AssociationField::new("value", "inquiries.field_value")
                ->setFormTypeOptions(['choice_label' => "title", "choice_translation_domain" => "messages"]),

            AssociationField::new("deadline", "inquiries.field_deadline")
                ->setFormTypeOptions(['choice_label' => "title", "choice_translation_domain" => "messages"]),

            FormField::addTab("admin.inquiries.title_contact")->onlyOnForms(),
            FormField::addPanel("admin.inquiries.title_contact")->onlyOnForms(),

            EmailField::new("contactEmail", "inquiries.field_email")->onlyOnForms(),

            TextField::new("contactPhone", "inquiries.field_phone")->onlyOnForms(),

            AssociationField::new("type", "inquiries.field_type")
                ->setFormTypeOptions(['choice_label' => "title", "choice_translation_domain" => "messages"]),

            AssociationField::new("personalContact", "inquiries.field_personal_contact")
                ->setFormTypeOptions(['choice_label' => function (PersonalContact $p) {
                    return "#" . $p->getId() . ": " . $p->getName() . " " . $p->getSurName();
                }, "choice_translation_domain" => "messages"])->onlyOnForms(),

            AssociationField::new("companyContact", "inquiries.field_company_contact")
                ->setFormTypeOptions(['choice_label' => function (CompanyContact $p) {
                    return "#" . $p->getId() . ": " . $p->getCompanyName();
                }, "choice_translation_domain" => "messages"])->onlyOnForms(),

            FormField::addTab("admin.inquiries.title_others")->onlyOnForms(),
            FormField::addPanel("admin.inquiries.title_others")->onlyOnForms(),

            AssociationField::new("state", "inquiries.field_state")
                ->setFormTypeOptions(['choice_label' => "title", "choice_translation_domain" => "messages"]),
        ];
    }

}
