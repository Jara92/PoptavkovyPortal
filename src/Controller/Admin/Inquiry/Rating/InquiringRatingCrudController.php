<?php

namespace App\Controller\Admin\Inquiry\Rating;

use App\Entity\Inquiry\Rating\InquiringRating;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class InquiringRatingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InquiringRating::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle("index", "admin.ratings.inquiring_title")
            ->setHelp("index", "Tato stránka obsahuje všechna hodnocení dodavatelů, která vytváří autoři poptávek.");
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id", "ID")->hideOnForm(),

            AssociationField::new("inquiry", "admin.inquiries.field_inquiry"),

            AssociationField::new("target", "admin.inquiries.field_supplier"),
            AssociationField::new("author", "admin.inquiries.field_supplier"),

            NumberField::new("rating", "admin.ratings.field_rating")
                ->setRequired(false)
                ->setFormTypeOption("attr", ["min" => 1, "max" => 5]),

            BooleanField::new("isPublished", "admin.ratings.field_is_published"),

            TextareaField::new("targetNote", "admin.ratings.field_supplierNote")->hideOnIndex(),

            TextareaField::new("note", "admin.ratings.field_note")->hideOnIndex(),
        ];
    }

}
