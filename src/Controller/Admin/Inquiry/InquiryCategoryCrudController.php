<?php

namespace App\Controller\Admin\Inquiry;

use App\Entity\Inquiry\InquiryCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InquiryCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InquiryCategory::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
