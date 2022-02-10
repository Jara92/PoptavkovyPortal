<?php

namespace App\Controller\Admin\Inquiry;

use App\Entity\Inquiry\InquiryType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InquiryTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InquiryType::class;
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
