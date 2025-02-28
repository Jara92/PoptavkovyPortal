<?php

namespace App\Controller\Admin;

use App\Business\Operation\InquiryOperation;
use App\Business\Service\Inquiry\InquiryCategoryService;
use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquiryCategory;
use App\Entity\Inquiry\PersonalContact;
use App\Enum\Entity\InquiryState;
use App\Enum\Entity\InquiryType;
use App\Helper\InquiryStateHelper;
use App\Helper\InquiryTypeHelper;
use App\Repository\Inquiry\InquiryCategoryRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class InquiryCrudController extends AbstractCrudController
{
    public function __construct(
        private InquiryOperation $inquiryOperation,
    )
    {
    }

    public function approve(EntityManagerInterface $manager, AdminContext $context): Response
    {
        /** @var Inquiry $inquiry */
        $inquiry = $context->getEntity()->getInstance();

        $inquiry->setState(InquiryState::STATE_ACTIVE);

        $this->updateEntity($manager, $inquiry);

        // TODO: FIX REDIRECT after save

        return new Response(status: Response::HTTP_OK);
    }

    public function configureActions(Actions $actions): Actions
    {
        $approve = Action::new("approveInquiry", "btn_approve", "fa fa-check")
            ->displayAsLink()
            ->linkToCrudAction("approve");

        return $actions
            ->add(Crud::PAGE_INDEX, Crud::PAGE_DETAIL)
            ->add(Crud::PAGE_DETAIL, $approve);
    }

    public static function getEntityFqcn(): string
    {
        return Inquiry::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('title')
            ->add('alias')
            ->add("categories")
            ->add("deadline")
            ->add(ChoiceFilter::new("type", "inquiries.field_type")
                ->setChoices(InquiryTypeHelper::translationStringCases())
                ->setFormTypeOption("translation_domain", "messages"))
            ->add(ChoiceFilter::new("state", "inquiries.field_state")
                ->setChoices(InquiryStateHelper::translationStringCases())
                ->setFormTypeOption("translation_domain", "messages"))
            ->add("value");
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Inquiry $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->inquiryOperation->updateInquiry($entityInstance);
    }

    public function new(AdminContext $context): KeyValueStore|RedirectResponse|Response
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

            FormField::addPanel("admin.inquiries.title_aditional_information")->onlyOnForms(),
            AssociationField::new("categories", "admin.inquiries.field_categories")
                // ->setQueryBuilder() does not work as expected.
                ->setFormTypeOption(
                    'query_builder', function (InquiryCategoryRepository $repo) {
                    return $repo->getSubcategoriesQuery();
                })
                ->setFormTypeOptions(['choice_label' => function (InquiryCategory $c) {
                    if ($c->getParent()) {
                        return $c->getParent()->getTitle() . " | " . $c->getTitle();
                    } else {
                        return $c->getTitle();
                    }
                }])
                ->onlyOnForms(),

            AssociationField::new("region", "inquiries.field_region")
                ->setFormTypeOptions(['choice_label' => "title", "choice_translation_domain" => "messages"]),

            TextField::new("city", "inquiries.field_city")->hideOnIndex(),

            AssociationField::new("value", "inquiries.field_value")
                ->setFormTypeOptions(['choice_label' => "title", "choice_translation_domain" => "messages"]),

            AssociationField::new("deadline", "inquiries.field_deadline")
                ->setFormTypeOptions(['choice_label' => "title", "choice_translation_domain" => "messages"]),

            FormField::addTab("admin.inquiries.title_contact")->onlyOnForms(),
            FormField::addPanel("admin.inquiries.title_contact")->onlyOnForms(),

            EmailField::new("contactEmail", "inquiries.field_email")->onlyOnForms(),

            TextField::new("contactPhone", "inquiries.field_phone")->onlyOnForms(),

            /* TODO: 2 fields for type and state - missing EnumField and ChoiceField does not work well.
             * Enum values are accepted on forms.
             * String values are accepted views.
             */
            ChoiceField::new("type", "inquiries.field_type")
                ->setChoices(InquiryTypeHelper::translationCases())
                ->setFormTypeOptions(["choice_translation_domain" => "messages"])->onlyOnForms(),

            ChoiceField::new("type", "inquiries.field_type")
                ->setChoices(InquiryTypeHelper::translationStringCases())
                ->setFormTypeOptions(["choice_translation_domain" => "messages"])->hideOnForm(),

            AssociationField::new("personalContact", "inquiries.field_personal_contact")
                ->setFormTypeOptions(['choice_label' => function (PersonalContact $p) {
                    return "#" . $p->getId() . ": " . $p->getName() . " " . $p->getSurName();
                }, "choice_translation_domain" => "messages"])->onlyOnDetail(),

            AssociationField::new("companyContact", "inquiries.field_company_contact")
                ->setFormTypeOptions(['choice_label' => function (CompanyContact $p) {
                    return "#" . $p->getId() . ": " . $p->getCompanyName();
                }, "choice_translation_domain" => "messages"])->onlyOnDetail(),

            FormField::addTab("admin.inquiries.title_time")->onlyOnForms(),

            DateTimeField::new("createdAt", "admin.inquiries.field_created_at")
                ->setRequired(false)
                ->renderAsChoice()
                ->setFormat('d.M.Y H:m'),
            DateTimeField::new("updatedAt", "admin.inquiries.field_updated_at")
                ->setRequired(false)
                ->renderAsChoice()
                ->setFormat('d.M.Y H:m')->hideOnForm(),
            DateTimeField::new("publishedAt", "admin.inquiries.field_published_at")
                ->setRequired(false)
                ->renderAsChoice()
                ->setFormat('d.M.Y H:m'),

            FormField::addPanel("admin.inquiries.title_auto_remove")->onlyOnForms(),
            DateTimeField::new("removeNoticeAt", "admin.inquiries.field_remove_notice_at")
                ->setRequired(false)
                ->renderAsChoice()
                ->setFormat('d.M.Y H:m')
                ->onlyOnForms(),
            DateTimeField::new("removeAt", "admin.inquiries.field_remove_at")
                ->setRequired(false)
                ->renderAsChoice()
                ->setFormat('d.M.Y H:m')
                ->onlyOnForms(),

            FormField::addTab("admin.inquiries.title_others")->onlyOnForms(),

            ChoiceField::new("state", "inquiries.field_state")
                ->setChoices(InquiryStateHelper::translationCases())
                ->setFormTypeOptions(["choice_translation_domain" => "messages"])->onlyOnForms(),

            ChoiceField::new("state", "inquiries.field_state")
                ->setChoices(InquiryStateHelper::translationStringCases())
                ->setFormTypeOptions(["choice_translation_domain" => "messages"])->hideOnForm(),
        ];
    }

}
