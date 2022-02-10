<?php

namespace App\Controller\Admin\Inquiry;

use App\Entity\Inquiry\Deadline;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DeadlineCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Deadline::class;
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
