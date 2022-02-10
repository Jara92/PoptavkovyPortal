<?php

namespace App\Controller\Admin\Inquiry;

use App\Entity\Inquiry\InquiryValue;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InquiryValueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InquiryValue::class;
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
