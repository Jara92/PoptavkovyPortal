<?php

namespace App\Controller\Admin;

use App\Entity\Inquiry\Inquiry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InquiryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Inquiry::class;
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
