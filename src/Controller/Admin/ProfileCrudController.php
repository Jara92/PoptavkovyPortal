<?php

namespace App\Controller\Admin;

use App\Entity\Inquiry\CompanyContact;
use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\PersonalContact;
use App\Entity\Profile;
use App\Enum\Entity\InquiryState;
use App\Enum\Entity\InquiryType;
use App\Helper\InquiryStateHelper;
use App\Helper\InquiryTypeHelper;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ProfileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Profile::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            // TODO: avatar
            IdField::new("id", "ID")->hideOnForm(),

            AssociationField::new("user", "admin.profiles.field_user"),

            BooleanField::new("isPublic", "admin.profiles.field_is_public"),

            TextareaField::new('description', "inquiries.field_description")->hideOnIndex(),

            UrlField::new("web", "admin.profiles.field_web")->hideOnIndex(),
            UrlField::new("facebook", "admin.profiles.field_facebook")->hideOnIndex(),
            UrlField::new("linkedin", "admin.profiles.field_linkedin")->hideOnIndex(),
        ];
    }

}
