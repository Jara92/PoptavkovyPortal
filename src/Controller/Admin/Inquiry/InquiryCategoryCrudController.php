<?php

namespace App\Controller\Admin\Inquiry;

use App\Entity\Inquiry\InquiryCategory;
use App\Repository\Inquiry\InquiryCategoryRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Service\Attribute\Required;

class InquiryCategoryCrudController extends AbstractCrudController
{
    #[Required]
    public InquiryCategoryRepository $repository;

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
            TextareaField::new('description')->hideOnIndex()
                ->setRequired(false),

            AssociationField::new('parent', "admin.inquiries.field_parent_category")
                ->setFormTypeOptions([
                    // Show only root categories
                    'choices' => $this->repository->findRootCategories(),
                ])

        ];
    }

}
