<?php

namespace App\Controller\Admin;

use App\Entity\Inquiry\Rating\SupplierRating;
use App\Entity\User\UserRating;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class UserRatingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserRating::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle("index", "admin.ratings.user_title")
            ->setHelp("index", "Tato stránka obsahuje všechna uživatelská hodnocení.");
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id", "ID")->hideOnForm(),

            AssociationField::new("author", "admin.inquiries.field_author"),

            NumberField::new("rating", "admin.ratings.field_rating")
                ->setRequired(false)
                ->setFormTypeOption("attr", ["min" => 1, "max" => 5]),

            BooleanField::new("isPublished", "admin.ratings.field_is_published"),

            TextareaField::new("targetNote", "admin.ratings.field_userNote"),

            TextareaField::new("note", "admin.ratings.field_note")->hideOnIndex(),
        ];
    }

}
