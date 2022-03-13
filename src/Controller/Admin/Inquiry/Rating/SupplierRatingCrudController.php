<?php

namespace App\Controller\Admin\Inquiry\Rating;

use App\Entity\Inquiry\Rating\SupplierRating;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class SupplierRatingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SupplierRating::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle("index", "admin.ratings.supplier_title")
            ->setHelp("index", "Tato stránka obsahuje všechna hodnocení poptávajících, která vytváří dodavatelé poptávek.");
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id", "ID")->hideOnForm(),

            AssociationField::new("inquiry", "admin.inquiries.field_inquiry"),

            AssociationField::new("author", "admin.inquiries.field_author"),

            BooleanField::new("realizedInquiry", "admin.ratings.field_realized_inquiry"),

            NumberField::new("rating", "admin.ratings.field_rating")
                ->setRequired(false)
                ->setFormTypeOption("attr", ["min" => 1, "max" => 5]),

            TextareaField::new("inquiringNote", "admin.ratings.field_inquiringNote")->hideOnIndex(),

            TextareaField::new("note", "admin.ratings.field_note")->hideOnIndex(),
        ];
    }

}
