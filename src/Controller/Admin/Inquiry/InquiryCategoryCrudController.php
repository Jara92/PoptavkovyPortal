<?php

namespace App\Controller\Admin\Inquiry;

use App\Entity\Inquiry\InquiryCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InquiryCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InquiryCategory::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextField::new('alias'),
            TextareaField::new('description')->hideOnIndex(),

            // TODO: check for cycle when updating
            AssociationField::new("parent", "admin.inquiries.field_parent_category")
                ->setFormTypeOptions(['choice_label' => "title"])
        ];
    }

}
