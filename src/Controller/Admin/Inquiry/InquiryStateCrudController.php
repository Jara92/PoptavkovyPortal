<?php

namespace App\Controller\Admin\Inquiry;

use App\Entity\Inquiry\InquiryState;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InquiryStateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InquiryState::class;
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
